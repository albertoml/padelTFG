define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html',
    'text!templates/tournament/inscription.html',
    'text!templates/tournament/observation.html',
    'text!templates/common/genericModal.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, DataTableTemplate, InscriptionTemplate, ObservationTemplate, genericModalTemplate, literals) {
 
    var TournamentsView = Backbone.View.extend({
        el: '.tournaments',
        events: {
            'click button[id="doInscription"]': 'doInscription',
            'click button[id="insertObservation"]': 'addObservation',
            'click button[id="deleteObservation"]': 'deleteObservation',
            'click button[id="saveInscription"]': 'saveInscription'
        },
        initialize: function(model, params){
            var _self = this;
            this.params = params;
            if(!_.isUndefined(model)){
                _self.model = model;
                $.ajax({
                    type: 'GET',
                    async: false,
                    url: params.getTournaments,
                    success: function (response) {
                        _self.tournaments = response.tournament;
                    }
                });
            }
        },
        render: function() {
            var _self = this;
            var template = _.template(DataTableTemplate, {
                tableId : 'tournamentsTable'
            });
            _self.$el.html(template);
            $('#tournamentsTable').dataTable({
                'search': false,
                'number': false,
                'modelid': 'tournament',
                'bAutoWidth': false,
                'identifier': 'identifier',
                'bSort': false,
                'aoColumns': [{
                    'sTitle': literals.tournamentsFields.name,
                    'mData': 'name',
                    'mRender': function (data, type, full) {
                        return data;
                    }
                }, {
                    'sTitle': literals.tournamentsFields.admin,
                    'mData': 'admin',
                    'mRender': function (data, type, full) {
                        if(!_.isUndefined(data) && !_.isNull(data)){
                            return data.name;
                        }
                        else{
                            return '';
                        }
                    }
                }, {
                    'sTitle': literals.tournamentsFields.startInscriptionDate,
                    'mData': 'startInscriptionDate',
                    'mRender': function (data, type, full) {
                        if(!_.isUndefined(data) && !_.isNull(data)){
                            return new Date(data.date).toString('d-MM-yyyy');
                        }
                        else{
                            return '';
                        }
                    }
                }, {
                    'sTitle': literals.tournamentsFields.startGroupDate,
                    'mData': 'startGroupDate',
                    'mRender': function (data, type, full) {
                        if(!_.isUndefined(data) && !_.isNull(data)){
                            return new Date(data.date).toString('d-MM-yyyy');
                        }
                        else{
                            return '';
                        }
                    }
                }, {
                    'sTitle': literals.tournamentsFields.status,
                    'mData': 'status',
                    'mRender': function (data, type, full) {
                        if(!_.isUndefined(data) && !_.isNull(data)){
                            return data.value
                        }
                        else{
                            return '';
                        }
                    }
                }, {
                    'sTitle': literals.tournamentsFields.options,
                    'mRender': function (data, type, full) {
                        var button = "<button id='doInscription' name=" + full.id + " title='" + full.name + "'>" + literals.doInscription + "</button>";
                        return button;
                    }
                }],
                aaData: _self.tournaments
            });
        },
        doInscription: function(e){
            var _self = this;
            var categories = _self.getCategories(e.target.name);
            var modal = _self.renderModal(e.target.title, e.target.name);
            $(modal).modal();
            $('#' + this.modalID + ' .modal-dialog').css({'width':'600px'});
            this.setElement(_self.$el.add('#' + this.modalID));
            $("#category").select2({
                data: categories
            });
            $('#pair').select2({
              ajax: {
                url: this.params.searchUser,
                delay: 250,
                processResults: function (data) {
                    var users = [];
                    var nameLogged = this.$element[0].name;
                    _.each(data.user, function(u){
                        if(nameLogged != u.name){
                            var user = {
                                'id' : u.id,
                                'text' : u.name
                            }
                            users.push(user);
                        }
                    });
                    return {
                        results: users
                    };
                }
              }
            });
        },
        renderModal: function(tournamentName, tournamentId){
            var _self = this;
            this.modalID = 'myInscriptionModal';
            var myModal = $(this.modalID);
            var buttons = '<button id="saveInscription" title="' + literals.saveButtonTitle + '" class="buttonInscription btn btn-default col-lg-6">' + literals.saveButtonTitle + '</button>' + 
            '<button id="cancelInscription" title="' + literals.cancelButtonTitle + '" class="buttonInscription btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';
            var modalContent = _self.loadInscriptionInfo(tournamentId);
            var modal = _.template(genericModalTemplate, {
                id: this.modalID,
                title: literals.modalTitleInscription + " " + tournamentName,
                content: modalContent,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });
            return modal;
        },
        loadInscriptionInfo: function(tournamentId){
            var _self = this;
            var template = _.template(InscriptionTemplate, {
                profileId : 'bodyInscription',
                dataLiterals : literals,
                tournamentId: tournamentId,
                userLogged: _self.model.get('name')
            });
            return template;
        },
        getCategories: function(tournamentId){
            var _self = this;
            var urlToSend = _self.params.getCategories.replace('{idTournament}', tournamentId);
            var categories = [];
            $.ajax({
                type: 'GET',
                async: false,
                url: urlToSend,
                success: function (response) {
                    _.each(response.category, function(cat){
                        var category = {
                            'id' : cat.id,
                            'text' : cat.name
                        }
                        categories.push(category);
                    });
                }
            });
            return categories;
        },
        addObservation: function(){
            var _self = this;
            var template = _.template(ObservationTemplate, {
                dataLiterals : literals
            });
            $('#observations').append(template);
        },
        deleteObservation: function(e){
            if(e.target.tagName == "I"){
                e.target.parentElement.parentElement.parentElement.remove();
            }
            else{
                e.target.parentElement.parentElement.remove();
            }
        },
        getObservations: function(){
            var observations = [];
            var observationsInvalid = false;
            _.each($('.observation'), function(obs){
                var date = obs.getElementsByClassName('date')[0].value;
                var fromHour = obs.getElementsByClassName('fromHour')[0].value;
                var toHour = obs.getElementsByClassName('toHour')[0].value;

                if(date!="" && fromHour!="" && toHour!=""){
                    var observation = {
                        "date": date,
                        "fromHour": fromHour,
                        "toHour": toHour
                    };
                    observations.push(observation);
                }
                else{
                    observationsInvalid=true;
                }
            });
            if(observationsInvalid){
                alert(literals.someObservationsAreInvalid);
            }
            return observations;
        },
        getPair: function(user1, user2){
            var _self = this;
            var pair = "";
            var urlToSend = this.params.getPair2Id.replace('{idUser1}', user1);
            urlToSend = urlToSend.replace('{idUser2}', user2);
            $.ajax({
                type: 'GET',
                url: urlToSend,
                async: false,
                success: function (response) {

                    if(!_.isEmpty(response.pair)){
                        pair = response.pair[0].id;           
                    }
                    else{
                        pair = _self.createPair(user1, user2);
                    }
                },
                error: function(msg){
                    pair = null;
                }
            });
            return pair;
        },
        createPair: function(user1, user2){

            var _self = this;
            var pair = {
                'user1' : user1,
                'user2' : user2
            };
            var result = "";
            $.ajax({
                type: 'POST',
                url: _self.params.pairAdminURL,
                data: JSON.stringify(pair),
                async: false,
                success: function (response) {
                    result = response.pair.id;
                },
                error: function(msg){
                    result = null;
                }
            });
            return result;
        },
        saveInscription: function(){
            var _self = this;
            var observations = _self.getObservations();
            var user1 = this.model.get('id').toString();
            var user2= $('#pair').val();
            var pair = this.getPair(user1, user2);
            var category= $('#category').val();
            var tournamentId= $('.bodyInscription')[0].id;
            var inscriptionToSend = {
                "category" : category,
                "tournament" : tournamentId,
                "pair" : [{
                    "pairId" : pair,
                    "observations" : observations
                }]
            };
            $.ajax({
                type: 'POST',
                url: _self.params.inscriptionAdminURL,
                data: JSON.stringify(inscriptionToSend),
                success: function (response) {
                    $('.common').trigger('showSuccesAlert', [literals.successMessageTextInscription]);
                    $('#' + _self.modalID).modal('hide');
                    _self.render();
                    $('.inscriptions').trigger('refreshSection');
                    $('.pairs').trigger('refreshSection');
                },
                error: function(msg){
                    $('.common').trigger('showErrorAlert', [msg.responseJSON.error]);
                }
            });


        }
    });
    return TournamentsView;
});