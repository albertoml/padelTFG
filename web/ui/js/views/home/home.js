define([
    'backbone', 
    'underscore',
    'models/user/user',
    'models/userRole/userRole',
    'i18n!nls/homeLiterals.js',
    'views/basicInfo/basicInfo',
    'views/annotations/annotations',
    'views/tournamentAdmin/tournamentAdmin',
    'views/tournaments/tournaments',
    'views/inscriptions/inscriptions',
    'views/games/games',
    'views/pairs/pairs',
    'text!templates/home/section.html',
    'text!templates/home/listTree.html',
    'text!templates/home/listTreeAdmin.html',
    'text!templates/home/pageContent.html',
    'text!templates/common/genericModal.html',
    'text!templates/tournament/newTournamentStep1.html',
    'text!templates/tournament/newTournamentStep3.html',
    'text!templates/tournament/category.html',
    'text!templates/tournament/range.html'], 
    function(Backbone, _, UserModel, UserRoleModel, Literals, BasicInfoView, AnnotationsView, TournamentAdminView,
        TournamentsView, InscriptionsView, GamesView, PairsView, SectionTemplate, ListTreeTemplate, 
        ListTreeAdmin, PageContentTemplate, genericModalTemplate, NewTournamentStep1Template, NewTournamentStep3Template, CategoryTemplate, RangeTemplate) {

    var HomeView = Backbone.View.extend({
        el: '.wrapperHome',
        events: {
            'click #createNewTournament' : 'createNewTournament',
            'click button[id="nextToStep2NewTournament"]' : 'nextToStep2NewTournament',
            'click button[id="nextToStep3NewTournament"]' : 'nextToStep3NewTournament',
            'click button[id="insertCategory"]': 'addCategory',
            'click button[id="deleteCategory"]': 'deleteCategory',
            'click button[id="backToStep1NewTournament"]' : 'backToStep1NewTournament',
            'click button[id="backToStep2NewTournament"]' : 'backToStep2NewTournament',
            'click button[id="insertRange"]' : 'insertRangeToStep3',
            'click button[id="deleteRange"]' : 'deleteRange',
            'click button[id="saveTournament"]' : 'saveTournament'
        },
        initialize: function(params){
            this.sections = Literals.sections;
            this.model = {};
            this.params = params;
            this.genders = this.getGenders();
            this.dataTemplateStep1 = [
                {
                    'key': 'name',
                    'type': 'text',
                    'text': Literals.newTournamentFields.name,
                    'visible': true
                },
                {
                    'key': 'startInscriptionDate',
                    'type': 'date',
                    'text': Literals.newTournamentFields.startInscriptionDate,
                    'visible': true
                },
                {
                    'key': 'endInscriptionDate',
                    'type': 'date',
                    'text': Literals.newTournamentFields.endInscriptionDate,
                    'visible': true,
                    'editable': false
                },
                {
                    'key': 'startGroupDate',
                    'type': 'date',
                    'text': Literals.newTournamentFields.startGroupDate,
                    'visible': true,
                    'editable': false
                },
                {
                    'key': 'endGroupDate',
                    'type': 'date',
                    'text': Literals.newTournamentFields.endGroupDate,
                    'visible': true,
                    'editable': false
                },
                {
                    'key': 'startFinalDate',
                    'type': 'date',
                    'text': Literals.newTournamentFields.startFinalDate,
                    'visible': true,
                    'editable': false
                },
                {
                    'key': 'endFinalDate',
                    'type': 'date',
                    'text': Literals.newTournamentFields.endFinalDate,
                    'visible': true,
                    'editable': false
                },
                {
                    'key': 'registeredLimit',
                    'type': 'number',
                    'text': Literals.newTournamentFields.registeredLimit,
                    'visible': true
                },
                {
                    'key': 'image',
                    'type': 'file',
                    'text': Literals.newTournamentFields.image,
                    'visible': true
                }

            ];
        },
        render: function() {
            var _self = this;
            _self.$el.html('');
            var template = _.template(ListTreeTemplate, {
                sections: _self.sections
            });
            _self.$el.append(template);
            var template = _.template(PageContentTemplate, {
            });
            _self.$el.append(template);
            _.each(this.sections, function(section){
                var template = _.template(SectionTemplate, {
                    section: section
                });
                _self.$('.content-home').append(template);
            });
            _self.model = _self.getModel();
        },
        getModel: function(){
            var _self = this;
            var idUser = localStorage.getItem('idUser');
            var userModel = new UserModel({id: idUser});
            userModel.fetch({
                success: function(model){
                    _self.userModel = model;
                    _self.renderSections();
                }
            });
            var userRoleModel = new UserRoleModel({id: idUser});
            userRoleModel.fetch({
                success: function(modelAdmin){
                    if(!_.isEmpty(modelAdmin.get('tournament'))){
                        _self.renderAdminTreePanel(modelAdmin.get('tournament'), false); 
                        _self.renderAdminSections(modelAdmin.get('tournament'));        
                    }         
                }
            });
            $('#sidebar-wrapper').smint({
                'scrollSpeed' : 800
            });
        },
        renderSections: function(){
            var basicInfo = new BasicInfoView(this.userModel, this.params);
            basicInfo.render();
            var annotations = new AnnotationsView(this.userModel, this.params);
            annotations.render();
            var tournaments = new TournamentsView(this.userModel, this.params);
            tournaments.render();
            var inscriptions = new InscriptionsView(this.userModel, this.params);
            inscriptions.render();
            var games = new GamesView(this.userModel, this.params);
            games.render();
            var pairs = new PairsView(this.userModel, this.params);
            pairs.render();
        },
        renderAdminTreePanel: function(tournaments, onlyTournaments){
            var template = _.template(ListTreeAdmin, {
                tournaments: tournaments,
                literals: Literals,
                onlyTournaments: onlyTournaments
            });
            if(onlyTournaments){
                $('#createNewTournament').parent().prepend(template)            
            }
            else{
                $('#menu').append(template);
            }  
        },
        renderAdminSections: function(tournaments){
            var _self = this;
            _.each(tournaments, function(t){
                var tr = {
                    "text" : t.name,
                    "key" : 'tournamentAdmin' + t.id
                }
                var template = _.template(SectionTemplate, {
                    section: tr
                });
                _self.$('.content-home').append(template);
                var tournamentAdminView = new TournamentAdminView(_self.userModel, _self.params, tr.key, t);
                tournamentAdminView.render();
            });

            $('#sidebar-wrapper').smint({
                'scrollSpeed' : 800
            });
        },
        isDate: function(string) { 
            var ExpReg = /^(0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[/\\/](19|20)\d{2}$/
            return (ExpReg.test(string));
        },
        createNewTournament: function(e){
            var _self = this;
            var modal = _self.renderModalStep1();
            $(modal).modal();
            $('#' + this.modalID + ' .modal-dialog').css({'width':'700px'});
            this.setElement(_self.$el.add('#' + this.modalID));

            $('.startInscriptionDateNewTournament input').datepicker({
                format: "dd/mm/yyyy",
                startDate: new Date(),
                language: "en",
                weekStart: 1,
                daysOfWeekHighlighted: "5,6,0",
                autoclose: true
            });
            $('.startInscriptionDateNewTournament input').on('changeDate', function(ev){
                if(!_self.isDate($(this).val())){
                    $('.endInscriptionDateNewTournament input').attr('disabled', 'true');
                }
                else{
                    $('.endInscriptionDateNewTournament input').removeAttr('disabled');
                    $('.endInscriptionDateNewTournament input').datepicker("remove");
                    $('.endInscriptionDateNewTournament input').datepicker({
                        format: "dd/mm/yyyy",
                        startDate: $('.startInscriptionDateNewTournament input').val(),
                        language: "en",
                        weekStart: 1,
                        daysOfWeekHighlighted: "5,6,0",
                        autoclose: true
                    });
                }
            });
            
            $('.endInscriptionDateNewTournament input').on('changeDate', function(ev){
                if(!_self.isDate($(this).val())){
                    $('.startGroupDateNewTournament input').attr('disabled', 'true');
                }
                else{
                    $('.startGroupDateNewTournament input').removeAttr('disabled');
                    $('.startGroupDateNewTournament input').datepicker("remove");
                    $('.startGroupDateNewTournament input').datepicker({
                        format: "dd/mm/yyyy",
                        startDate: $('.endInscriptionDateNewTournament input').val(),
                        language: "en",
                        weekStart: 1,
                        daysOfWeekDisabled: "1,2,3,4",
                        daysOfWeekHighlighted: "5,6,0",
                        autoclose: true
                    });
                }
            });

            $('.startGroupDateNewTournament input').on('changeDate', function(ev){
                if(!_self.isDate($(this).val())){
                    $('.endGroupDateNewTournament input').attr('disabled', 'true');
                }
                else{
                    $('.endGroupDateNewTournament input').removeAttr('disabled');
                    $('.endGroupDateNewTournament input').datepicker("remove");
                    $('.endGroupDateNewTournament input').datepicker({
                        format: "dd/mm/yyyy",
                        startDate: $('.startGroupDateNewTournament input').val(),
                        language: "en",
                        weekStart: 1,
                        daysOfWeekDisabled: "1,2,3,4",
                        daysOfWeekHighlighted: "5,6,0",
                        autoclose: true
                    });
                }
            });
            
            $('.endGroupDateNewTournament input').on('changeDate', function(ev){
                if(!_self.isDate($(this).val())){
                    $('.startFinalDateNewTournament input').attr('disabled', 'true');
                }
                else{
                    $('.startFinalDateNewTournament input').removeAttr('disabled');
                    $('.startFinalDateNewTournament input').datepicker("remove");
                    $('.startFinalDateNewTournament input').datepicker({
                        format: "dd/mm/yyyy",
                        startDate: $('.endGroupDateNewTournament input').val(),
                        language: "en",
                        weekStart: 1,
                        daysOfWeekDisabled: "1,2,3,4",
                        daysOfWeekHighlighted: "5,6,0",
                        autoclose: true
                    });
                }
            });
            
            $('.startFinalDateNewTournament input').on('changeDate', function(ev){
                if(!_self.isDate($(this).val())){
                    $('.endFinalDateNewTournament input').attr('disabled', 'true');
                }
                else{
                    $('.endFinalDateNewTournament input').removeAttr('disabled');
                    $('.endFinalDateNewTournament input').datepicker("remove");
                    $('.endFinalDateNewTournament input').datepicker({
                        format: "dd/mm/yyyy",
                        startDate: $('.startFinalDateNewTournament input').val(),
                        language: "en",
                        weekStart: 1,
                        daysOfWeekDisabled: "1,2,3,4",
                        daysOfWeekHighlighted: "5,6,0",
                        autoclose: true
                    });
                }
            });
            
            return false;
        },
        renderModalStep1: function(){
            var _self = this;
            this.modalID = 'myNewTournamentModal';
            var myModal = $(this.modalID);
            var buttons = '<button id="nextToStep2NewTournament" title="' + Literals.nextStepButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-6">' + Literals.nextStepButtonTitle + '</button>' + 
            '<button id="cancelTournament" title="' + Literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + Literals.cancelButtonTitle + '</button>';
            var modalContent = _.template(NewTournamentStep1Template, {
                dataTemplate: _self.dataTemplateStep1,
                profileId: 'newTournamentStep1'
            });
            var modal = _.template(genericModalTemplate, {
                id: this.modalID,
                title: Literals.modalTitleNewTournamentBasicInfo,
                content: modalContent,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });
            return modal;
        },
        backToStep1NewTournament: function(){
            var _self = this;
            $('#newTournamentStep2').fadeOut('slow', function(){
                $('#newTournamentStep1').fadeIn('slow');
            });
            $('#' + this.modalID + ' .modal-header > h4').text(Literals.modalTitleNewTournamentBasicInfo);
            $('#' + this.modalID + ' .modal-footer').html('<button id="nextToStep2NewTournament" title="' + 
                Literals.nextStepButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-6">' + 
                Literals.nextStepButtonTitle + '</button>' + '<button id="cancelTournament" title="' + 
                Literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + 
                Literals.cancelButtonTitle + '</button>');
        },
        saveStep1Fields: function(){
            this.newTournamentToCreate = {
                'name': $('.nameNewTournament input[type="text"]').val(),
                'startInscriptionDate': $('.startInscriptionDateNewTournament input').val(),
                'endInscriptionDate': $('.endInscriptionDateNewTournament input').val(),
                'startGroupDate': $('.startGroupDateNewTournament input').val(),
                'endGroupDate': $('.endGroupDateNewTournament input').val(),
                'startFinalDate': $('.startFinalDateNewTournament input').val(),
                'endFinalDate': $('.endFinalDateNewTournament input').val(),
                'registeredLimit': $('.registeredLimitNewTournament input').val(),
                'image': $('.imageNewTournament input').val()
            };
        },
        nextToStep2NewTournament: function(){
            var _self = this;
            _self.saveStep1Fields();
            if(_self.newTournamentToCreate.name != "" && _self.newTournamentToCreate.startGroupDate != "" && _self.newTournamentToCreate.endGroupDate != ""){
                $('#newTournamentStep1').fadeOut('slow', function(){
                    _self.renderModalStep2();
                });     
            }
            else{
                alert(Literals.nameAndGroupDatesNotEmpty);
            }
            
        },
        renderModalStep2: function(){
            var _self = this;
            if($('#newTournamentStep2').length == 0){
                $('#' + this.modalID + ' .modal-body').append('<div id="newTournamentStep2" ' +
                'class="newTournamentStep2 col-lg-12" style="display:inline-block;"><div id="categories"></div>' +
                '<div id="addCategory"><button style="margin-top:15px" id="insertCategory">' + Literals.addCategory + '</button></div></div>');
                var categoryTemplate = _.template(CategoryTemplate, {
                    dataLiterals: Literals.categoryFields
                });
                $('#categories').append(categoryTemplate);
                $('.selectGender').select2({
                    data: _self.genders
                });
            }
            else{
                $('#newTournamentStep2').fadeIn('slow');
            }
            $('#' + this.modalID + ' .modal-header > h4').text(Literals.modalTitleNewTournamentCategory);
            $('#' + this.modalID + ' .modal-footer').html('<button id="backToStep1NewTournament" title="' + 
                Literals.backStepButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-2">' + 
                Literals.backStepButtonTitle + '</button>' + '<button id="saveTournament" title="' + 
                Literals.saveButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-4">' + 
                Literals.saveButtonTitle + '</button>' + '<button id="cancelTournament" title="' + 
                Literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + 
                Literals.cancelButtonTitle + '</button>');
        },
        addCategory: function(){
            var _self = this;
            var template = _.template(CategoryTemplate, {
                dataLiterals : Literals.categoryFields
            });
            $('#categories').append(template);
            $('.selectGender').last().select2({
                data: _self.genders
            });
        },
        deleteCategory: function(e){
            if(e.target.tagName == "I"){
                e.target.parentElement.parentElement.parentElement.remove();
            }
            else{
                e.target.parentElement.parentElement.remove();
            }
        },
        getGenders: function(){
            var _self = this;
            var genders = [];
            $.ajax({
                type: 'GET',
                url: _self.params.getGenders,
                success: function (response) {
                    _.each(response.genders, function(g, index){
                        var gen = {
                            'id' : g,
                            'text' : g
                        }
                        genders.push(gen);
                    });
                }
            });
            return genders;
        },
        getCategories: function(){
            var categories = [];
            var categoriesInvalid = false;
            _.each($('.category'), function(cat){
                var name = cat.getElementsByClassName('name')[0].value;
                var gender = cat.getElementsByClassName('selectGender')[0].value;
                var registeredLimit = cat.getElementsByClassName('registeredLimit')[0].value;
                var registeredMin = cat.getElementsByClassName('registeredMin')[0].value;

                if(name!="" && gender!=Literals.categoryFields.selectGenderPlaceholder){
                    var category = {
                        "name": name,
                        "gender": gender,
                        "registeredLimitMax": registeredLimit,
                        "registeredLimitMin": registeredMin
                    };
                    categories.push(category);
                }
                else{
                    categoriesInvalid=true;
                }
            });
            if(categoriesInvalid && categories.length > 0){
                alert(Literals.someCategoriesAreInvalid);
            }
            return categories;
        },
        saveStep2Fields: function(){
            this.newTournamentToCreateCategories = {
                'category' : this.getCategories()
            };
        },
        saveTournament: function(){
            var _self = this;
            _self.saveStep2Fields();
            if(confirm(Literals.areSureCreateTournament)){
                this.newTournamentToCreate.admin = _self.userModel.get('email');
                $.ajax({
                    type: 'POST',
                    url: _self.params.tournamentAdminURL,
                    data: JSON.stringify(_self.newTournamentToCreate),
                    success: function (response) {
                        $('.tournaments').trigger('refreshSection');
                        _self.renderAdminTreePanel(response, true);
                        _self.renderAdminSections(response);
                        _self.saveCategories(response.tournament.id);
                    },
                    error: function (message) {
                        $('.common').trigger('showErrorAlert', [message.key]);
                    }
                });
            }
        },
        saveCategories: function(tournamentId){
            var _self = this;
            var url = _self.params.addCategoryToTournament.replace('{idTournament}', tournamentId);
            $.ajax({
                type: 'POST',
                url: url,
                data: JSON.stringify(_self.newTournamentToCreateCategories),
                success: function (response) {
                    $('.common').trigger('showSuccesAlert', [Literals.successMessageTournamentSaved]);
                    $('#' + _self.modalID).modal('hide');
                },
                error: function (message) {
                    $('.common').trigger('showErrorAlert', [message.key]);
                }
            });
        }
        /*renderModalStep3: function(){
            var _self = this;
            if($('#newTournamentStep3').length == 0){
                var modalStep3Content = _.template(NewTournamentStep3Template, {
                    profileId: 'newTournamentStep3',
                    dataLiterals: Literals.rangeFields
                });
                $('#' + this.modalID + ' .modal-body').append('<div id="newTournamentStep3" ' +
                'class="newTournamentStep3 col-lg-12" style="display:inline-block;">' + modalStep3Content + '</div>');
            }
            else{
                $('#newTournamentStep3').fadeIn('slow');
            }
            $('#' + this.modalID + ' .modal-header > h4').text(Literals.modalTitleNewTournamentSchedule);
            $('#' + this.modalID + ' .modal-footer').html('<button id="backToStep2NewTournament" title="' + 
                Literals.backStepButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-2">' + 
                Literals.backStepButtonTitle + '</button>' + '<button id="nextToStep4NewTournament" title="' + 
                Literals.nextStepButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-4">' + 
                Literals.nextStepButtonTitle + '</button>' + '<button id="cancelTournament" title="' + 
                Literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + 
                Literals.cancelButtonTitle + '</button>');

        },
        backToStep2NewTournament: function(){
            var _self = this;
            $('#newTournamentStep3').fadeOut('slow', function(){
                $('#newTournamentStep2').fadeIn('slow');
            });
            $('#' + this.modalID + ' .modal-header > h4').text(Literals.modalTitleNewTournamentCategory);
            $('#' + this.modalID + ' .modal-footer').html('<button id="backToStep1NewTournament" title="' + 
                Literals.backStepButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-2">' + 
                Literals.backStepButtonTitle + '</button>' + '<button id="nextToStep3NewTournament" title="' + 
                Literals.nextStepButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-6">' + 
                Literals.nextStepButtonTitle + '</button>' + '<button id="cancelTournament" title="' + 
                Literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + 
                Literals.cancelButtonTitle + '</button>');
        },
        nextToStep3NewTournament: function(){
            var _self = this;
            _self.saveStep2Fields();
            if(_self.newTournamentToCreate.categories.length > 0){
                $('#newTournamentStep2').fadeOut('slow', function(){
                    _self.freeDays = _self.getFreeDaysForGroups();
                    _self.renderModalStep3();
                });     
            }
            else{
                alert(Literals.categoryNotEmpty);
            }
        },
        insertRangeToStep3: function(){
            var _self = this;
            var template = _.template(RangeTemplate, {
                dataLiterals: Literals.rangeFields
            });
            $('#ranges').append(template);
            $('.selectRange').last().select2({
                placeholder: Literals.rangeFields.selectRangePlaceholder
            });
            $('.selectDays').last().select2({
                data: _self.freeDays,
                placeholder: Literals.rangeFields.selectDaysPlaceholder
            }).on("select2:select", function(e) {
                _self.updateFreeDays(true);
            }).on("select2:unselect", function(e) {
                _self.updateFreeDays(false);
            });
            _self.updateFreeDays(true);
        },
        deleteRange: function(e){
            if(e.target.tagName == "I"){
                e.target.parentElement.parentElement.parentElement.remove();
            }
            else{
                e.target.parentElement.parentElement.remove();
            }
            this.updateFreeDays(false);
        },
        getFreeDaysForGroups: function(){
            var _self = this;
            var freeDates = [];

            var startAux = _self.newTournamentToCreate.startGroupDate.split("/");
            var startDate = new Date(startAux[2], startAux[1] - 1, startAux[0]);
            var endAux = _self.newTournamentToCreate.endGroupDate.split("/");
            var endDate = new Date(endAux[2], endAux[1] - 1, endAux[0]);

            var textDates = [];
            textDates[0] = Literals.SundayDay;
            textDates[5] = Literals.FridayDay;
            textDates[6] = Literals.SaturdayDay;


            while(startDate <= endDate){
                if(startDate.getDay() == 5 || startDate.getDay() == 6 || startDate.getDay() == 0){
                    var date = {
                        'id' : textDates[startDate.getDay()] + startDate.getDate().toString(),
                        'text' : textDates[startDate.getDay()] + " " + startDate.getDate()
                    }
                    freeDates.push(date);
                }
                startDate.setDate(startDate.getDate() + 1);
            }
            return freeDates;
        },
        updateFreeDays: function(enable){
            var _self = this;
            var selectedDays = [];
            if(enable){
                _.each($('.selectDays'), function(sel, index){
                    _.each(sel.selectedOptions, function(opt){
                        if(selectedDays.indexOf(opt.value) == -1){
                            selectedDays.push(opt.value);
                        }
                    });
                });
            }
            else{
                selectedDays = _.pluck(_self.freeDays, 'id');
                _.each($('.selectDays'), function(sel, index){
                    _.each(sel.selectedOptions, function(opt){
                        var arrayIndex = selectedDays.indexOf(opt.value);
                        if(arrayIndex != -1){
                            selectedDays.splice(arrayIndex, 1);
                        }
                    });
                });
            }
            _.each(selectedDays, function(day){
                $('option[value=' + day + ']').prop('disabled', enable);
                _.each($('.selectDays'), function(s, index){
                    $('.selectDays:eq(' + index + ')').select2();
                });
            })
        },*/
    });
    return HomeView;
});