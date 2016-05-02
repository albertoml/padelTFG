define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html',
    'text!templates/common/genericModal.html',
    'text!templates/tournamentAdmin/sectionAdmin.html',
    'text!templates/tournamentAdmin/closeInscription.html',
    'text!templates/tournamentAdmin/observation.html',
    'text!templates/tournamentAdmin/inscription.html',
    'text!templates/tournamentAdmin/pillsInscriptions.html',
    'text!templates/tournamentAdmin/groupBox.html',
    'text!templates/tournamentAdmin/newGroupBox.html',
    'text!templates/tournamentAdmin/insertShedule.html',
    'text!templates/tournamentAdmin/range.html',
    'text!templates/games/score.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, DataTableTemplate, genericModalTemplate, sectionAdminTemplate, 
        closeInscriptionTemplate, observationTemplate, addInscriptionTemplate, pillsInscriptionTemplate, 
        groupBoxTemplate, newGroupTemplate, insertScheduleTemplate, RangeTemplate, ScoreTemplate, literals) {
 
    var TournamentAdminView = Backbone.View.extend({
        events: {
            'refreshSection' : 'render',
            'click button[id="closeTournamentInscription"]' : 'closeTournamentInscription',
            'click button[id="editGroupsView"]' : 'getGroupsWhenTournamentIsClosed',
            'click button[id="verifyCloseInscriptions"]' : 'verifyCloseInscriptions',
            'click button[id="verifyAddInscriptions"]' : 'verifyAddInscriptions',
            'click button[id="addInscription"]' : 'addInscriptions',
            'click button[id="viewObservations"]' : 'viewObservation',
            'click button[id="deleteInscription"]' : 'deleteInscription',
            'click button[id="deleteItemInscription"]' : 'deleteItemInscription',
            'click button[id="addItemInscription"]' : 'addItemInscription',
            'click button[id="deleteObservation"]' : 'deleteObservation',
            'click button[id="addObservation"]' : 'addObservation',
            'click button[id="saveEditGroups"]' : 'saveEditGroups',
            'click button[id="saveEditGroupsAndDoMatchs"]' : 'saveEditGroupsAndDoMatchs',
            'click button[id="newGroupButtonAdd"]' : 'newGroupButton',
            'click button[id="deleteGroupBox"]' : 'deleteGroupBox',
            'click button[id="changeGame"]' : 'changeGameDate',
            'click button[id="addScore"]' : 'addGameScore',
            'click button[id="saveScore"]' : 'saveScore',
            'click button[id="showGameCalendar"]' : 'showGameCalendar',
            'click button[id="startTournament"]' : 'startTournament',
            'click button[id="insertRange"]' : 'insertRangeToShedule',
            'click button[id="deleteRange"]' : 'deleteRange',
            'click button[id="backToEditGroups"]' : 'backToEditGroups',
            'click button[id="saveShedule"]' : 'saveShedule'
        },
        initialize: function(model, params, elTournament, tournamentModel){
            var _self = this;
            _self.params = params;
            _self.userModel = model;
            _self.tournamentModel = tournamentModel;
            _self.newGroupIdBox = 0;
            _self.el = "." + elTournament;
            _self.$el = $(_self.el);
            _self.tournamentModel.fetch({
                success: function(model){
                    _self.render();
                }
            });
        },
        render: function() {
            var _self = this;
            var template = _.template(sectionAdminTemplate, {
                dataLiterals : literals,
                tournamentId: _self.tournamentModel.get('id'),
                tournamentStatus: _self.tournamentModel.get('status').value
            });
            _self.$el.html(template);

            if(_self.tournamentModel.get('status').value == literals.tournamentStatus.InInscriptionDate || _self.tournamentModel.get('status').value == literals.tournamentStatus.InGroupDate){
                if(!_.isUndefined(_self.categoryIdActivePills)){
                    _self.viewTournamentInscriptions(_self.categoryIdActivePills);
                }
                else{
                    _self.viewTournamentInscriptions(0);
                }
            }
            else if(_self.tournamentModel.get('status').value == literals.tournamentStatus.MatchsDone){
                if(!_.isUndefined(_self.categoryIdActivePills)){
                    _self.viewTournamentGames(_self.categoryIdActivePills);
                }
                else{
                    _self.viewTournamentGames(0);
                }
            }
        },
        startTournament: function(){
            var _self = this;
            var objToSend = {
                'status' : literals.tournamentStatus.InInscriptionDate            }
            $.ajax({
                type: 'PUT',
                data: JSON.stringify(objToSend),
                url: _self.params.tournamentAdminURL + '/' + _self.tournamentModel.get('id'),
                success: function (response) {
                    _self.tournamentModel.attributes = response.tournament;
                    _self.render();
                }
            })
        },
        preProcessViewTournamentAdminSection: function(categories, categoryId, typeMode){
            var _self = this;
            var templateCategories = [];
            var index = 0;
            _.each(categories, function(category, catKey){
                var catInfo = catKey.split(';');
                var catName = catInfo[0].replace(' ', '_');
                var tableTemplate = _.template(DataTableTemplate, {
                    tableId : "tableCategory" + catName
                });
                if(categoryId == 0 && index == 0){
                    var activePills = 1;
                }
                else{
                    if(categoryId == catInfo[1]){
                        var activePills = 1;
                    }
                    else{
                        var activePills = 0;
                    }
                }
                var templateCategory = {
                    categoryKey : catInfo[0],
                    typeMode: typeMode,
                    categoryName : catName,
                    categoryId : catInfo[1],
                    dataTableCategory : tableTemplate,
                    active : activePills
                };
                templateCategories.push(templateCategory);
                index += 1;
            });
            var templateForRender = _.template(pillsInscriptionTemplate, {
                dataTemplate : templateCategories,
                dataLiterals : literals,
                tournamentStatus: _self.tournamentModel.get('status').value
            });
            $('#viewTournamentAdminSectionTemplate' + _self.tournamentModel.get('id')).html(templateForRender);
        },
        viewTournamentInscriptions: function(categoryId){
            var _self = this;
            var urlToSend = _self.params.getInscriptionsByTournament.replace('{idTournament}', _self.tournamentModel.get('id'));
            $.ajax({
                type: 'GET',
                url: urlToSend,
                success: function (response) {  
                    _self.preProcessViewTournamentAdminSection(response.inscription, categoryId, literals.InscriptionsMode);
                    _.each(response.inscription, function(category, catKey){
                        var catInfo = catKey.split(';');
                        var catName = catInfo[0].replace(' ', '_');

                        $('#tableCategory' + catName).dataTable({
                            'search': false,
                            'number': false,
                            'modelid': 'category' + catName,
                            'bAutoWidth': false,
                            'drawCallback': function( settings ) {
                                _self.deleteOptionsColumnForInscriptionsTable(settings);
                            },
                            'identifier': 'identifier',
                            'bSort': false,
                            'aoColumns': [{
                                'sTitle': literals.viewInscriptionsAdmin.player1,
                                'mData': '',
                                'mRender': function (data, type, full) {
                                    return full.pair.user1.name + " " + full.pair.user1.lastName;
                                },
                            }, {
                                'sTitle': literals.viewInscriptionsAdmin.player2,
                                'mData': '',
                                'mRender': function (data, type, full) {
                                    return full.pair.user2.name + " " + full.pair.user2.lastName;
                                },
                            }, {
                                'sTitle': literals.viewInscriptionsAdmin.observations,
                                'mData': 'hasObservations',
                                'sClass': 'observationCell',
                                'mRender': function (data, type, full) {
                                    var observations = "";
                                    if(data){
                                        var urlToSend = _self.params.getObservations.replace('{idInscription}', full.id);
                                        $.ajax({
                                            type: 'GET',
                                            async: false,
                                            url: urlToSend,
                                            success: function (response) {
                                                observations = "";
                                                observations += '<div id="observationsContainer' + full.id + '">';
                                                _.each(response.observation, function(obs, index){
                                                    observations += "<p class='observationIns" + full.id + "'>"
                                                    observations += new Date(obs.date.date).toString('d/MM/yyyy');
                                                    observations += "  -  ";
                                                    observations += obs.fromHour;
                                                    observations += "  -  ";
                                                    observations += obs.toHour;
                                                    observations += '<button modelid="' + full.id + '" style="margin-left:10px" name="' + obs.id + '" id="deleteObservation"><i class="fa fa-times"></i></button>';
                                                    observations += "</p>"
                                                });
                                                observations += '</div>';
                                            },
                                            error: function(msg){
                                                observations = "error";
                                            }
                                        });
                                        return observations;
                                    }
                                    else{
                                        observations += '<div id="observationsContainer' + full.id + '">';
                                        observations += literals.no;
                                        observations += '</div>';
                                        return observations;
                                    }
                                }
                            }, {
                                'sTitle': literals.viewInscriptionsAdmin.options,
                                'mData': '',
                                'sClass': 'optionsCell',
                                'mRender': function (data, type, full) {
                                    var button = '<button name=' + full.id + ' class="popoverObs btn btn-default">' + literals.addObservation + '</button>';
                                    button += "<button alt=" + literals.deleteInscription + " id='deleteInscription' name=" + full.id + " class='deleteButton'><i class='fa fa-trash-o'></i></button>";
                                    return button;
                                }
                            }],
                            aaData: category
                        });
                    });
                    _.each($('.popoverObs'), function(e){
                        $(e).popover({
                            title: literals.addObservations,
                            content: _self.getObservationTemplate(e.name),
                            html: true,
                            placement: "left"
                        }).on('shown.bs.popover', function(){
                            $('.date').datepicker({
                                format: "dd/mm/yyyy",
                                startDate: new Date(),
                                language: "en",
                                weekStart: 1,
                                daysOfWeekHighlighted: "5,6,0",
                                autoclose: true
                            });
                        }).on('hidden.bs.popover', function (e) {
                            $(e.target).data("bs.popover").inState.click = false;
                        });
                    });
                }
            });
        },
        deleteOptionsColumnForInscriptionsTable: function(settings){
            var _self = this;
            if(_self.tournamentModel.get('status').value == literals.tournamentStatus.InGroupDate){
                _.each(settings.aoColumns, function(col, index){
                    if(col.sClass == "optionsCell"){
                        $(settings.nTable).dataTable().fnSetColumnVis(index, false, false);
                    }
                })
            }
        },
        viewTournamentGames: function(categoryId){
            var _self = this;

            if(_.isNull(_self.gamesDone) || _.isUndefined(_self.gamesDone)){
                var urlToSend = _self.params.getGamesByTournament.replace('{idTournament}', _self.tournamentModel.get('id'));
                $.ajax({
                    type: 'GET',
                    url: urlToSend,
                    async: false,
                    success: function (response) {
                        _self.gamesDone = response.game;
                    }
                })
            }
            _self.preProcessViewTournamentAdminSection(_self.gamesDone, categoryId, literals.GamesMode);
            _.each(_self.gamesDone, function(games, catKey){
                var catInfo = catKey.split(';');
                var catName = catInfo[0].replace(' ', '_');

                $('#tableCategory' + catName).dataTable({
                    'search': false,
                    'number': false,
                    'modelid': 'category' + catName,
                    'bAutoWidth': false,
                    'identifier': 'identifier',
                    'bSort': false,
                    'aoColumns': [{
                        'sTitle': literals.gamesFields.gameDate,
                        'mData': 'date',
                        'mRender': function (data, type, full) {
                            if(!_.isNull(data) && !_.isUndefined(data)){
                                return new Date(data.date).toString('d-MM-yyyy');
                            }
                            else{
                                return literals.gamesFields.DateNotAvailable;
                            }
                        }
                    }, {
                        'sTitle': literals.viewGamesAdmin.pair1,
                        'mData': '',
                        'mRender': function (data, type, full) {
                            return full.pair1.user1.name + " " + full.pair1.user2.name;
                        }
                    }, {
                        'sTitle': literals.viewGamesAdmin.pair2,
                        'mData': '',
                        'mRender': function (data, type, full) {
                            return full.pair2.user1.name + " " + full.pair2.user2.name;
                        }
                    }, {
                        'sTitle': literals.gamesFields.status,
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
                        'sTitle': literals.gamesFields.score,
                        'mData': 'score',
                        'mRender': function (data, type, full) {
                            if(!_.isUndefined(data) && !_.isNull(data) && !_.isEmpty(data)){
                                return data;
                            }
                            else{
                                return literals.gamesFields.GameNotPlayed;
                            }
                        }
                    }, {
                        'sTitle': literals.gamesFields.options,
                        'mRender': function (data, type, full) {
                            var button = "";
                            button += "<button alt=" + literals.changeDate + " id='changeGame' name=" + full.id + " class='deleteButton'>" + literals.changeDate + "</button>";    
                            if(!_.isUndefined(full.score) && !_.isNull(full.score) && !_.isEmpty(full.score)){
                                button += "<button method='modify' alt=" + literals.modifyScore + " id='addScore' name=" + full.id + " class='deleteButton'>" + literals.modifyScore + "</button>";
                            }
                            else{
                                button += "<button method='add' alt=" + literals.addScore + " id='addScore' name=" + full.id + " class='deleteButton'>" + literals.addScore + "</button>";
                            }

                            return button;
                        }
                    }],
                    aaData: games
                });
            })

            $('#calendar' + _self.tournamentModel.get('id')).fullCalendar({
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                defaultView: 'agendaDay',
                defaultDate: '2016-01-07',
                maxTime: "22:00:00",
                minTime: "8:00:00",
                allDaySlot: false,
                firstDay: 1,
                editable: true,
                selectable: true,
                eventLimit: true, // allow "more" link when too many events
                header: {
                    left: 'prev,next today',
                    center: 'title promptResource',
                    right: 'agendaDay,agendaWeek,month'
                },
                customButtons: {
                    promptResource: {
                        text: literals.fullCalendar.addTrack,
                        click: function() {
                            var title = prompt(literals.fullCalendar.addTrackAlert);
                            if (title) {
                                $('#calendar' + _self.tournamentModel.get('id')).fullCalendar(
                                    'addResource',
                                    { title: title },
                                    true // scroll to the new resource?
                                );
                            }
                        }
                    }
                },
                resourceRender: function(resource, cellEls) {
                    cellEls.on('click', function() {
                        if (confirm(literals.fullCalendar.confirmDeleteTrack + resource.title + '?')) {
                            $('#calendar' + _self.tournamentModel.get('id')).fullCalendar('removeResource', resource);
                        }
                    });
                },
                views: {
                },
                resources: [
                    { id: 'a', title: 'Room A' },
                    { id: 'b', title: 'Room B' },
                    { id: 'c', title: 'Room C' },
                    { id: 'd', title: 'Room D' }
                ],
                events: [
                    { id: '2', resourceId: 'a', start: '2016-01-07T09:00:00', end: '2016-01-07T14:00:00', title: 'event 2', backgroundColor: '#342' },
                    { id: '3', resourceId: 'b', start: '2016-01-07T12:00:00', end: '2016-01-08T06:00:00', title: 'event 3', backgroundColor: '#622' },
                    { id: '4', resourceId: 'c', start: '2016-01-07T07:30:00', end: '2016-01-07T09:30:00', title: 'event 4', backgroundColor: '#842' },
                    { id: '5', resourceId: 'd', start: '2016-01-07T10:00:00', end: '2016-01-07T15:00:00', title: 'event 5', backgroundColor: '#246' }
                ],

                select: function(start, end, jsEvent, view, resource) {
                    console.log(
                        'select',
                        start.format(),
                        end.format(),
                        resource ? resource.id : '(no resource)'
                    );
                },
                dayClick: function(date, jsEvent, view, resource) {
                    console.log(
                        'dayClick',
                        date.format(),
                        resource ? resource.id : '(no resource)'
                    );
                }
            });
        },
        showGameCalendar: function(e){
            var _self = this;
            if(e.target.textContent == literals.showGameCalendarButton){
                e.target.textContent = literals.hideGameCalendarButton;
                $('#showCalendar' + _self.tournamentModel.get('id')).show();
            }
            else{
                e.target.textContent = literals.showGameCalendarButton;
                $('#showCalendar' + _self.tournamentModel.get('id')).hide();
            }
        },
        renderObservationCell: function(inscriptionId){
            var _self = this;
            var observations = "";
            var urlToSend = _self.params.getObservations.replace('{idInscription}', inscriptionId);
            var observations = [];
            $.ajax({
                type: 'GET',
                async: false,
                url: urlToSend,
                success: function (response) {
                    observations = "";
                    _.each(response.observation, function(obs, index){
                        observations += "<p class='observationIns" + inscriptionId + "'>"
                        observations += new Date(obs.date.date).toString('d/MM/yyyy');
                        observations += "  -  ";
                        observations += obs.fromHour;
                        observations += "  -  ";
                        observations += obs.toHour;
                        observations += '<button modelid="' + inscriptionId + '" style="margin-left:10px" name="' + obs.id + '" id="deleteObservation"><i class="fa fa-times"></i></button>';
                        observations += "</p>"
                    });
                },
                error: function(msg){
                    observations = "error";
                }
            });
            return observations;
        },
        getObservationTemplate: function(inscriptionId){
            var template = _.template(observationTemplate, {
                dataLiterals : literals,
                inscription: inscriptionId
            });
            return template;
        },
        addInscriptions: function(e){
            var _self = this;
            var modalContent = '<div id = "InscriptionsItems">';
            modalContent += _.template(addInscriptionTemplate, {
                dataLiterals : literals.viewInscriptionsAdmin,
                userLogged: _self.userModel.get('name')
            });
            modalContent += '</div>';
            modalContent += '<div class="col-lg-4" style="margin-top:20px"><button id="addItemInscription">' + literals.addInscription + '</button></div>';
            var modal = _self.renderModalAddInscription(modalContent, e.target.name);
            $(modal).modal();
            $('.player1').last().select2({
              ajax: {
                url: this.params.searchUser,
                delay: 250,
                processResults: function (data) {
                    var users = [];
                    var nameLogged = this.$element[0].name;
                    _.each(data.user, function(u){
                        var user = {
                            'id' : u.id,
                            'text' : u.name + " " + u.lastName
                        }
                        users.push(user);
                    });
                    return {
                        results: users
                    };
                }
              }
            });
            $('.player2').last().select2({
              ajax: {
                url: this.params.searchUser,
                delay: 250,
                processResults: function (data) {
                    var users = [];
                    var nameLogged = this.$element[0].name;
                    _.each(data.user, function(u){
                        var user = {
                            'id' : u.id,
                            'text' : u.name + " " + u.lastName
                        }
                        users.push(user);
                    });
                    return {
                        results: users
                    };
                }
              }
            });
        },
        renderModalAddInscription: function(modalContent, categoryId){
            var _self = this;
            this.modalIDAddInscription = 'myAddInscriptionAdmin';
            var myModal = $(this.modalIDCloseTournament);
            var buttons = '<button name="' + categoryId + '" id="verifyAddInscriptions" title="' + literals.addInscriptions + '" class="buttonSaveTournament btn btn-default col-lg-6">' + literals.addInscriptions + '</button>' + 
             '<button id="cancelAddInscriptions" title="' + literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';
            var modal = _.template(genericModalTemplate, {
                id: this.modalIDAddInscription,
                title: literals.AddTournamentInscriptionModalTitle,
                content: modalContent,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });

            $(modal).modal();
            $('#' + this.modalIDAddInscription + ' .modal-dialog').css({'width':'900px'});
            this.setElement(_self.$el.add('#' + this.modalIDAddInscription));
            return false;
        },
        deleteInscription: function(e){
            var _self = this;
            if(e.target.tagName == "I"){
                var node = e.target.parentElement;
            }
            else{
                var node = e.target;
            }
            if(confirm(literals.AreSureDeleteInscription)){
                $.ajax({
                    type: 'DELETE',
                    url: _self.params.inscriptionAdminURL + "/" + node.name,
                    complete: function (response) {
                        if(response.status == 200){
                            $('.common').trigger('showSuccesAlert', [literals.successMessageDeleteInscription]);
                            $('#' + node.closest('table').id).dataTable().fnDeleteRow(node.closest('tr'), null, true);
                            $('.inscriptions').trigger('refreshSection');
                        }
                        else{
                            $('.common').trigger('showErrorAlert', [literals.errorMessageDeleteInscription]);
                        }   
                    }
                });
            }
        },
        deleteObservation: function(e){
            var _self = this;
            if(e.target.tagName == "I"){
                var node = e.target.parentElement;
            }
            else{
                var node = e.target;
            }
            if(confirm(literals.AreSureDeleteInscription)){
                $.ajax({
                    type: 'DELETE',
                    url: _self.params.observationAdminURL + "/" + node.name,
                    complete: function (response) {
                        if(response.status == 200){
                            var row = node.closest('td');
                            $(node.parentElement).remove();
                            if($('.' + node.parentElement.className + ':visible').length == 0){
                                var observations = "";
                                observations += '<div id="observationsContainer' + $(node).attr('modelid') + '">';
                                observations += literals.no;
                                observations += '</div>';
                                $(row).html(observations);
                            }
                            $('.inscriptions').trigger('refreshSection');
                        }
                        else{
                            $('.common').trigger('showErrorAlert', [literals.errorMessageDeleteInscription]);
                        }   
                    }
                });
            }
        },
        addObservation: function(e){
            var _self = this;
            var observationsInvalid = false;
            var date = $('.observation .date').val();
            var fromHour = $('.observation .fromHour').val();
            var toHour = $('.observation .toHour').val();

            if(date!="" && fromHour!="" && toHour!=""){
                var observation = {
                    "date": date,
                    "fromHour": fromHour,
                    "toHour": toHour
                };
            }
            else{
                observationsInvalid=true;
            }
            if(observationsInvalid){
                alert(literals.observationsSomeEmptyFields);
            }
            else{
                var observationsToSend = {
                    "inscription" : e.target.name,
                    "observation" : observation
                };
                $.ajax({
                    type: 'POST',
                    url: _self.params.observationAdminURL,
                    data: JSON.stringify(observationsToSend),
                    success: function (response) {
                        $('.popover').popover('hide');
                        var observations = _self.renderObservationCell(e.target.name);
                        $('#observationsContainer' + e.target.name).html(observations);
                    },
                    error: function(msg){
                        $('.common').trigger('showErrorAlert', [msg.responseJSON.error]);
                    }
                });
            }
        },
        renderModalCloseInscriptions: function(modalContent, buttons){
            var _self = this;
            this.modalIDCloseTournament = 'myCloseInscriptionAdmin';
            var myModal = $(this.modalIDCloseTournament);
            var modal = _.template(genericModalTemplate, {
                id: this.modalIDCloseTournament,
                title: literals.closeTournamentInscriptionModalTitle,
                content: modalContent,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });

            $(modal).modal();
            $('#' + this.modalIDCloseTournament + ' .modal-dialog').css({'width':'900px'});
            this.setElement(_self.$el.add('#' + this.modalIDCloseTournament));
            return false;
        },
        closeTournamentInscription: function(){
            var _self = this;
            var urlToSend = _self.params.countInscriptions.replace('{idTournament}', _self.tournamentModel.get('id'));
            $.ajax({
                type: 'GET',
                url: urlToSend,
                success: function (response) {
                    _self.categoriesTournament = response.inscription;
                    var template = _.template(closeInscriptionTemplate, {
                        data: response.inscription,
                        dataLiterals: literals
                    });
                    var buttons = '<button id="verifyCloseInscriptions" title="' + literals.closeTournamentInscriptionButton + '" class="buttonSaveTournament btn btn-default col-lg-6">' + literals.closeTournamentInscriptionButton + '</button>' + 
                    '<button id="cancelCloseInscriptions" title="' + literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';
                    _self.renderModalCloseInscriptions(template, buttons);
                }
            });       
        },
        verifyCloseInscriptions: function(){
            var _self = this;
            var numberPairs = _self.getNumberPairs();

            if(!_.isNull(numberPairs)){
                if(confirm(literals.DoGroupConfirm)){
                    _self.verifyCloseInscriptionOkConfirm();
                }
            }
            else{
                alert(literals.errorNumberGroups);
            }
        },
        verifyCloseInscriptionOkConfirm: function(){
            var _self = this;
            var numberPairs = _self.getNumberPairs();
            var urlToSend = _self.params.closeInscriptionTournament.replace('{idTournament}', _self.tournamentModel.get('id'));
            $.ajax({
                type: 'PUT',
                url: urlToSend,
                data: JSON.stringify(numberPairs),
                success: function (response) {
                    $('#closeInscriptionModalTemplate').fadeOut('slow', function(){
                        _self.renderGroupView(response.tournament);
                    });
                    _self.tournamentModel.fetch({
                        success: function(){
                            $(_self.el).trigger('refreshSection');
                        }
                    });
                }
            });
        },
        getNumberPairs: function(){
            var _self = this;
            var numPairs = {};
            var fail = false;
            _.each(_self.categoriesTournament.category, function(category, catKey){
                var catName = catKey.replace(' ', '_');
                var numGroupValue = $('.' + catName + 'NumGroup input').val();
                if(!_.isEmpty(numGroupValue)){
                    numPairs[catKey] = numGroupValue;
                }
                else{
                    fail = true;
                }
            });
            if(fail){
                return null;
            }
            else{
                return numPairs;
            }
        },
        deleteItemInscription: function(e){
            if(e.target.tagName == "I"){
                e.target.parentElement.parentElement.parentElement.remove();
            }
            else{
                e.target.parentElement.parentElement.remove();
            }
        },
        addItemInscription: function(){
            var _self = this;
            var template = _.template(addInscriptionTemplate, {
                dataLiterals : literals.viewInscriptionsAdmin,
                userLogged: _self.userModel.get('name')
            });
            $('#InscriptionsItems').append(template);
            $('.player1').last().select2({
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
                                'text' : u.name + " " + u.lastName
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
            $('.player2').last().select2({
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
                                'text' : u.name + " " + u.lastName
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
        verifyAddInscriptions: function(e){
            var _self = this;
            var pairs = _self.getCheckPairs();
            var inscriptionPairs = [];
            _.each(pairs, function(p){
                var objectPair = {
                    "pairId" : p,
                    "observations" : ""
                }
                inscriptionPairs.push(objectPair);
            })
            var inscriptionToSend = {
                "category" : e.target.name,
                "tournament" : _self.tournamentModel.get('id'),
                "pair" : inscriptionPairs
            };
            $.ajax({
                type: 'POST',
                url: _self.params.inscriptionAdminURL,
                data: JSON.stringify(inscriptionToSend),
                success: function (response) {
                    $('.common').trigger('showSuccesAlert', [literals.successMessageTextInscription]);
                    $('#' + _self.modalIDAddInscription).modal('hide');
                    _self.categoryIdActivePills = e.target.name;
                    _self.render();
                    $('.inscriptions').trigger('refreshSection');
                    $('.pairs').trigger('refreshSection');
                },
                error: function(msg){
                    $('.common').trigger('showErrorAlert', [msg.responseJSON.error]);
                    $('#' + _self.modalIDAddInscription).modal('hide');
                    _self.categoryIdActivePills = e.target.name;
                    _self.render();
                }
            });
        },
        getCheckPairs: function(){
            _self = this;
            var pairsToCheck = [];
            var someErroneusPairs = false;
            _.each($('.inscriptionItem'), function(item){
                var player1 = $(item.getElementsByClassName('player1')).val();
                var player2 = $(item.getElementsByClassName('player2')).val();
                if(player1 != "PlayerUndefined" && player2 != "PlayerUndefined"){
                    var players = {
                        "user1" : player1,
                        "user2" : player2
                    }
                    pairsToCheck.push(players);
                }
                else{
                    someErroneusPairs = true;
                }
            });
            var objectToSend = {
                'pairs' : pairsToCheck
            }
            var inscriptionPairs = [];
            if(someErroneusPairs){
                if(confirm(literals.somePairsErroneusConfirm)){
                    inscriptionPairs = _self.checkPairs(objectToSend);
                }
            }
            else{
                inscriptionPairs = _self.checkPairs(objectToSend);
            }
            return inscriptionPairs;
        },
        checkPairs: function(pairs){
            var inscriptionPairs = [];
            $.ajax({
                type: 'POST',
                url: _self.params.checkAndCreatePairsByUsers,
                data: JSON.stringify(pairs),
                async: false,
                success: function (response) {
                    inscriptionPairs = response.pair;
                },
                error: function(msg){
                    $('.common').trigger('showErrorAlert', [literals.errorMessageGetPairsInfo]);
                }
            });
            return inscriptionPairs;
        },
        addEmptyGroup: function(toItem){
            if(toItem.find('#titleForEmptyGroup').length == 0){
                var nextIdGroupTo = toItem.find('section[id*="groupBox"]').last().attr('id').replace('groupBox', '')
                var template = _.template(newGroupTemplate, {
                    dataLiterals: literals,
                    nextIdGroupTo: nextIdGroupTo
                });
                toItem.append(template);  
            }
        },
        addGroupToGroupsView: function(category){
            var _self = this;
            var template = _.template(groupBoxTemplate, {
                groupId : 'newGroup' + _self.newGroupIdBox,
                groupName: literals.newGroupTitle,
                inscriptions: [],
                dataLiterals: literals
            });
            $(template).insertBefore('#groupsCategoryBox' + category + ' > #newGroupBoxSection');
            _self.makesEditableNames();
            _self.newGroupIdBox ++; 
        },
        makesEditableNames: function(){
            $('.groupNameEditableField').editable({
                type: 'text',
                mode: 'inline'
            }); 
        },
        renderGroupView: function(categories){
            var _self = this;

            $('#' + this.modalIDCloseTournament + ' .modal-body').append('<div id="groupsBox" ' + 
                'class="col-lg-12" style="display:inline-block;"></div>');
            _.each(categories, function(groups, catKey){
                if(!_.isEmpty(groups)){    

                    var catInfo = catKey.split(';');
                    var catName = catInfo[0];
                    var catId = catInfo[1];
                    $('#groupsBox').append('<div id="groupsCategoryBox' + catId + '" class="col-lg-12 groupsCategoryBox"><h3>' + catName + '</h3></div>');
                    var templateCategory = "";
                    _.each(groups, function(inscriptions, groupKey){
                        var groupInfo = groupKey.split(';');
                        var groupName = groupInfo[0];
                        var groupId = groupInfo[1];
                        var template = _.template(groupBoxTemplate, {
                            groupId : groupId,
                            groupName: groupName,
                            inscriptions: inscriptions,
                            dataLiterals: literals
                        });
                        templateCategory += template;
                    });
                    $('#groupsCategoryBox' + catId).append(templateCategory);
                    _self.addEmptyGroup($('#groupsCategoryBox' + catId));
                }
            });
            $('#' + this.modalIDCloseTournament + ' .modal-header > h4').text(literals.modalTitleEditGroups);
            $('#' + this.modalIDCloseTournament + ' .modal-footer').html('<button id="saveEditGroups" title="' + 
                literals.saveGroupsButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-4">' + 
                literals.saveGroupsButtonTitle + '</button>' + 
                '<button id="saveEditGroupsAndDoMatchs" title="' + 
                literals.saveAndDoMatchsButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-4">' + 
                literals.saveAndDoMatchsButtonTitle + '</button>'
                 + '<button id="cancelEditGroups" title="' + 
                literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + 
                literals.cancelButtonTitle + '</button>');
        },
        getGroupsWhenTournamentIsClosed: function(){
            var _self = this;
            var urlToSend = _self.params.getInscriptionsByGroupForATournament.replace('{idTournament}', _self.tournamentModel.get('id'));
            $.ajax({
                type: 'GET',
                url: urlToSend,
                success: function (response) {
                    _self.renderModalCloseInscriptions('', 'buttons');
                    _self.renderGroupView(response.inscription);
                    setTimeout(function(){ _self.makesEditableNames(); }, 5);
                }
            });
        },
        newGroupButton: function(e){
            var _self = this;
            if(e.target.tagName == "I"){
                var node = e.target.parentElement;
            }
            else{
                var node = e.target;
            }
            if($(node.closest('.groupsCategoryBox')).find('#titleForEmptyGroup').length == 0){
                _self.addGroupToGroupsView($(node.closest('.groupsCategoryBox')).attr('id').replace('groupsCategoryBox', ''));
            } else {
                alert(literals.notAddGroupsWhenSomeAreEmpty)
            }
        },
        deleteGroupBox: function(e){
            $(e.target).closest('section').remove();
        },
        saveEditGroups: function(options){
            var _self = this;
            var validGroups = true;
            var tournamentGroups = {};
            _.each($('.groupsCategoryBox'), function(categoryDiv){
                var idCat = $(categoryDiv).attr('id').replace('groupsCategoryBox', '');
                var categoryGroups = [];
                _.each($(categoryDiv).find('section'), function(groupSection){
                    var sectionId = $(groupSection).attr('id');
                    if(sectionId != 'newGroupBoxSection'){
                        var groupName = $(groupSection).find('#groupName').text();
                        var pairsItems = $(groupSection).find('.groupBoxItem');
                        var pairs = [];
                        _.each(pairsItems, function(pairItem){
                            var pairId = $(pairItem).attr('name');
                            pairs.push(parseInt(pairId));
                        });
                        if(pairs.length < 2){
                            validGroups = false;
                        }
                        if(sectionId.indexOf('newGroup') > -1){
                            var groupId = 'newGroup';
                        }
                        else{
                            var groupId = parseInt(sectionId.replace('groupBox', ''));
                        }
                        var group = {
                            'name': groupName,
                            'category': parseInt(idCat),
                            'tournament': _self.tournamentModel.get('id'),
                            'numPairs': pairs.length,
                            'pairs' : pairs,
                            'groupId' : groupId
                        };
                        categoryGroups.push(group);
                    }
                });
                tournamentGroups[idCat] = categoryGroups;
            });

            if(!validGroups){
                alert(literals.errorGroupsInvalid);
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: _self.params.saveGroupsForATournament,
                    data: JSON.stringify(tournamentGroups),
                    success: function (response) {
                        if(!_.isUndefined(options) && !_.isUndefined(options.then) && _.isFunction(options.then)){
                            options.then(_self);
                        }else{
                            $('#' + _self.modalIDCloseTournament).modal('hide');
                        }
                    }
                });
            }
        },
        saveEditGroupsAndDoMatchs: function(){
            var _self = this;
            _self.saveEditGroups({then: _self.nextToinsertShedule});
        },
        renderInsertSchedule: function(){
            var _self = this;
            if($('#insertSchedule').length == 0){
                var modalInsertSheduleContent = _.template(insertScheduleTemplate, {
                    profileId: 'insertSchedule',
                    dataLiterals: literals.rangeFields
                });
                $('#' + this.modalIDCloseTournament + ' .modal-body').append(modalInsertSheduleContent);
            }
            else{
                $('#insertSchedule').fadeIn('slow');
            }
            $('#' + this.modalIDCloseTournament + ' .modal-header > h4').text(literals.modalTitleInsertTournamentSchedule);
            $('#' + this.modalIDCloseTournament + ' .modal-footer').html('<button id="backToEditGroups" title="' + 
                literals.backStepButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-2">' + 
                literals.backStepButtonTitle + '</button>' + '<button id="saveShedule" title="' + 
                literals.saveDoMatchsButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-4">' + 
                literals.saveDoMatchsButtonTitle + '</button>' + '<button id="cancelShedule" title="' + 
                literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + 
                literals.cancelButtonTitle + '</button>'); 

        },
        backToEditGroups: function(){
            var _self = this;
            $('#insertSchedule').fadeOut('slow', function(){
                $('#groupsBox').fadeIn('slow');
            });

            $('#' + this.modalIDCloseTournament + ' .modal-header > h4').text(literals.modalTitleEditGroups);
            $('#' + this.modalIDCloseTournament + ' .modal-footer').html('<button id="saveEditGroups" title="' + 
                literals.saveGroupsButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-4">' + 
                literals.saveGroupsButtonTitle + '</button>' + 
                '<button id="saveEditGroupsAndDoMatchs" title="' + 
                literals.saveAndDoMatchsButtonTitle + '" class="buttonSaveTournament btn btn-default col-lg-4">' + 
                literals.saveAndDoMatchsButtonTitle + '</button>'
                 + '<button id="cancelEditGroups" title="' + 
                literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + 
                literals.cancelButtonTitle + '</button>');
        },
        nextToinsertShedule: function(_self){
            $('#groupsBox').fadeOut('slow', function(){
                _self.freeDays = _self.getFreeDaysForGroups();
                _self.renderInsertSchedule();
            });
        },
        insertRangeToShedule: function(){
            var _self = this;
            var numRanges = $('#ranges').find('.range').length;

            var template = _.template(RangeTemplate, {
                dataLiterals: literals.rangeFields,
                numRange: numRanges
            });
            $('#ranges').append(template);

            $('.selectRange').last().tagEditor({
                placeholder: literals.rangeFields.selectRangePlaceholder,
                beforeTagSave: function(field, editor, tags, tag, val) {
                    hours = val.split('-');
                    var initial = "-1";
                    var final = "-1";
                    var invalid = false;
                    if(hours.length == 2){
                        _.each(hours, function(hourInput){
                            if(hourInput.indexOf(':') > -1){
                                hourWithMinutes = hourInput.split(':');
                                if(hourWithMinutes.length == 2){
                                    var hour = parseInt(hourWithMinutes[0]);
                                    var minutes = parseInt(hourWithMinutes[1]);
                                }
                            }
                            else{
                                var hour = parseInt(hourInput);
                            }
                            if(!$.isNumeric(hour) || !_.isUndefined(minutes) && !$.isNumeric(minutes)){
                                invalid = true;
                            }
                            else{
                                if(initial == "-1"){
                                    if(_.isUndefined(minutes)){
                                        initial = 'T' + hour + ':00:00';
                                    }
                                    else{
                                        initial = 'T' + hour + ':' + minutes + ':00';
                                    }
                                }
                                else{
                                    if(_.isUndefined(minutes)){
                                        final = 'T' + hour + ':00:00';
                                    }
                                    else{
                                        final = 'T' + hour + ':' + minutes + ':00';
                                    }
                                }
                            }
                        })
                    }
                    else{
                        invalid = true;
                    }

                    if(invalid){
                        alert(literals.rangeFields.invalidRangeFormat);
                        return false;
                    }
                    else{
                        var index = field.closest('.range').attr('id').replace('numRange' , '');
                        _self.saveRangeToSchedule(initial, final, parseInt(index));
                    }
                }
            });

            $('.selectDays').last().select2({
                data: _self.freeDays,
                placeholder: literals.rangeFields.selectDaysPlaceholder
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

            var startDate = new Date(_self.tournamentModel.get('startGroupDate').date);
            var endDate = new Date(_self.tournamentModel.get('endGroupDate').date);

            var textDates = [];
            textDates[0] = literals.SundayDay;
            textDates[5] = literals.FridayDay;
            textDates[6] = literals.SaturdayDay;


            while(startDate <= endDate){
                if(startDate.getDay() == 5 || startDate.getDay() == 6 || startDate.getDay() == 0){
                    var date = {
                        'id' : startDate.getDay() + '' + startDate.getDate(),
                        'text' : textDates[startDate.getDay()] + " " + startDate.getDate(),
                        'searchValue' : startDate.toString()
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
        },
        saveRangeToSchedule: function(initial, final, indexRange){
            if(_.isUndefined(this.tournamentToSaveRanges)){
                this.tournamentToSaveRanges = [];
            }
            if(_.isUndefined(this.tournamentToSaveRanges[indexRange])){
                this.tournamentToSaveRanges[indexRange] = [];
            }
            this.tournamentToSaveRanges[indexRange].push({
                fromHour: initial,
                toHour: final
            });
        },
        saveShedule: function(){
            var _self = this;

            var numberTracks = $('#selectTrack').val();
            numberTracks = parseInt(numberTracks);
            var tournamentRanges = [];
            _.each($('.range'), function(range, index){
                var dates = [];
                _.each($(range).find('#selectDays :selected'), function(date){
                    _.each(_self.freeDays, function(day){
                        if(day.id == date.value){
                            dates.push(day.searchValue);
                        }
                    })
                });
                var range = {
                    'dates' : dates,
                    'ranges' : _self.tournamentToSaveRanges[index]
                };
                tournamentRanges.push(range);
            });

            var tournamentObj = {
                'tournamentId' : _self.tournamentModel.get('id')
            };

            var rangesObj = {
                'track' : numberTracks,
                'scheduleRanges' : tournamentRanges,
                'tournament' : _self.tournamentModel.get('id')
            }

            /*$.ajax({
                type: 'POST',
                data: JSON.stringify(rangesObj),
                url: _self.params.scheduleAdminURL,
                success: function (response) {*/
                    $.ajax({
                        type: 'POST',
                        data: JSON.stringify(tournamentObj),
                        url: _self.params.doMatchsForATournament,
                        success: function (response) {
                            _self.gamesDone = response.game;
                            _self.tournamentModel.fetch({
                                success: function(){
                                    $('#' + _self.modalIDCloseTournament).modal('hide');
                                    _self.render();
                                }
                            })
                        }
                    });
                /*},
                error: function(error){
                    console.log(error);
                }
            });*/
        },
        changeGameDate: function(e){
            alert('NOT IMPLEMENTED YET');
        },
        addGameScore: function(e){
            var _self = this;
            var idGame = e.target.name; 
            var method = $(e.target).attr('method');
            var gameToReturn = {};
            _.each(_self.gamesDone, function(gamesCategory){
                _.each(gamesCategory, function(game){
                    if(game.id == idGame){
                        gameToReturn = game;
                    }
                })
            })
            _self.showScoreModal(gameToReturn, method);
        },
        showScoreModal: function(game, method){
            var _self = this;
            var modal = _self.renderScoreModal(game);
            
            $(modal).modal();
            $('#' + this.modalIDScoreModal + ' .modal-dialog').css({'width':'600px'});
            this.setElement(_self.$el.add('#' + this.modalIDScoreModal));


            $('.selectScore').select2({
                data: literals.scoreSelectOptions
            });
            if(method == 'modify'){
                var results = _self.parseScore(game.score);
                $('#set1Pair1').val(results[0]).change();
                $('#set1Pair2').val(results[1]).change();
                $('#set2Pair1').val(results[2]).change();
                $('#set2Pair2').val(results[3]).change();
                $('#set3Pair1').val(results[4]).change();
                $('#set3Pair2').val(results[5]).change();
            }
        },
        renderScoreModal: function(game){
            var _self = this;
            this.modalIDScoreModal = 'myScoreModal';
            var buttons = "";
            var modal = "";
            buttons += '<button id="cancelScore" title="' + literals.cancelButtonTitle + '" class="buttonInscription btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';
            buttons += '<button categoryId="' + game.category.id + '" name="' + game.id + '" id="saveScore" title="' + literals.saveButtonTitle + '" class="buttonObservations btn btn-default col-lg-6">' + literals.saveButtonTitle + '</button>';
            
            var template = _.template(ScoreTemplate, {
                dataLiterals : literals,
                content : game
            });

            modal = _.template(genericModalTemplate, {
                id: this.modalIDScoreModal,
                title: literals.modalTitleScore,
                content: template,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });
            return modal;
        },
        parseScore: function(scoreString){
            var setsVars = [];
            _.each(scoreString, function(c){
                if(c != '/' && c != ' '){
                    setsVars.push(parseInt(c));
                }
            })
            if(setsVars.length == 4){
                setsVars.push(-1);
                setsVars.push(-1);
            }
            return setsVars;
        },
        checkValidScore: function(){

            var setsPair1 = [$('#set1Pair1').val(), $('#set2Pair1').val(), $('#set3Pair1').val()];
            var setsPair2 = [$('#set1Pair2').val(), $('#set2Pair2').val(), $('#set3Pair2').val()];

            if(setsPair1[2] == -1 && setsPair2[2] == -1){
                var setPlayed = 2;
                var correctPlayed = 2;
            }
            else{
                var setPlayed = 3;
                var correctPlayed = 3;
            }
            var setWonPair1 = 0;
            var setWonPair2 = 0;
            var minimunSetsForWonMatch = 2;

            var scoreParsed = "";

            for(var i = 0; i < setPlayed; i++){
                if(setsPair1[i] > setsPair2[i]){
                    if(setsPair1[i] == 7 && (setsPair2[i] == 5 || setsPair2[i] == 6)){
                        setWonPair1 ++;
                        correctPlayed --;
                        scoreParsed += setsPair1[i] + '/' + setsPair2[i] + " "; 
                    }
                    else if(setsPair1[i] == 6 && setsPair2[i] >= 0 && setsPair2[i] <= 4){
                        setWonPair1 ++;
                        correctPlayed --;
                        scoreParsed += setsPair1[i] + '/' + setsPair2[i] + " ";
                    }
                }
                else{
                    if(setsPair2[i] == 7 && (setsPair1[i] == 5 || setsPair1[i] == 6)){
                        setWonPair2 ++;
                        correctPlayed --;
                        scoreParsed += setsPair1[i] + '/' + setsPair2[i] + " ";
                    }
                    else if(setsPair2[i] == 6 && setsPair1[i] >= 0 && setsPair1[i] <= 4){
                        setWonPair2 ++;
                        correctPlayed --;
                        scoreParsed += setsPair1[i] + '/' + setsPair2[i] + " ";
                    }
                }
            }

            if(setWonPair1 == minimunSetsForWonMatch && correctPlayed == 0){
                var score = {
                    status: literals.gameStatus.WonPair1,
                    score: scoreParsed.trim()
                };
                return score;
            }
            else if(setWonPair2 == minimunSetsForWonMatch && correctPlayed == 0){
                var score = {
                    status: literals.gameStatus.WonPair2,
                    score: scoreParsed.trim()
                };
                return score;
            }
            else{
                return null;
            }
        },
        saveScore: function(e){
            var _self = this;
            var idGame = e.target.name;
            var score = _self.checkValidScore();
            if(score != null){
                $.ajax({
                    type: 'PUT',
                    url: _self.params.gameAdminURL + '/' + idGame,
                    data: JSON.stringify(score),
                    success: function (response) {
                        $('.common').trigger('showSuccesAlert', [literals.successMessageSaveScore]);
                        _self.gamesDone = null;
                        _self.categoryIdActivePills = $(e.target).attr("categoryId");
                        _self.render();
                        $('#' + _self.modalIDScoreModal).modal('hide');
                    },
                    error: function(msg){
                        $('.common').trigger('showErrorAlert', [msg.responseJSON.error]);
                    }
                });
            }
            else{
                alert(literals.scoreIncorrect);
            }
        }
    });
    return TournamentAdminView;
});