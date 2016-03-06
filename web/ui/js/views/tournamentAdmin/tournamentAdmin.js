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
    'i18n!nls/homeLiterals.js'], function(Backbone, _, DataTableTemplate, genericModalTemplate, sectionAdminTemplate, closeInscriptionTemplate, observationTemplate, addInscriptionTemplate, pillsInscriptionTemplate, literals) {
 
    var TournamentAdminView = Backbone.View.extend({
        events: {
            'click button[id="closeTournamentInscription"]' : 'closeTournamentInscription',
            'click button[id="verifyCloseInscriptions"]' : 'verifyCloseInscriptions',
            'click button[id="verifyAddInscriptions"]' : 'verifyAddInscriptions',
            'click button[id="addInscription"]' : 'addInscriptions',
            'click button[id="viewObservations"]' : 'viewObservation',
            'click button[id="deleteInscription"]' : 'deleteInscription',
            'click button[id="deleteItemInscription"]' : 'deleteItemInscription',
            'click button[id="addItemInscription"]' : 'addItemInscription',
            'click button[id="deleteObservation"]' : 'deleteObservation',
            'click button[id="addObservation"]' : 'addObservation'
        },
        initialize: function(model, params, elTournament, tournamentModel){
            var _self = this;
            _self.params = params;
            _self.userModel = model;
            _self.tournamentModel = tournamentModel;
            _self.el = "." + elTournament;
            _self.$el = $(_self.el);
        },
        render: function() {
            var _self = this;
            var template = _.template(sectionAdminTemplate, {
                dataLiterals : literals,
                tournamentId: _self.tournamentModel.id
            });
            if(!_.isUndefined(_self.categoryIdActivePills)){
                _self.viewTournamentInscription(_self.categoryIdActivePills);
            }
            else{
                _self.viewTournamentInscription(0);
            }
            _self.$el.html(template);
        },
        viewTournamentInscription: function(categoryId){
            var _self = this;
            var urlToSend = _self.params.getInscriptionsByTournament.replace('{idTournament}', _self.tournamentModel.id);
            $.ajax({
                type: 'GET',
                url: urlToSend,
                success: function (response) {
                    var templateCategories = [];
                    var index = 0;
                    _.each(response.inscription, function(category, catKey){
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
                        dataLiterals : literals
                    });
                    $('#viewTournamentInscriptionSection' + _self.tournamentModel.id).html(templateForRender);
                    _.each(response.inscription, function(category, catKey){
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
            $('#' + this.modalID + ' .modal-dialog').css({'width':'600px'});
            this.setElement(_self.$el.add('#' + this.modalID));
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
            $('#' + this.modalIDAddInscription + ' .modal-dialog').css({'width':'700px'});
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
        renderModalCloseInscriptions: function(modalContent){
            var _self = this;
            this.modalIDCloseTournament = 'myCloseInscriptionAdmin';
            var myModal = $(this.modalIDCloseTournament);
            var buttons = '<button id="verifyCloseInscriptions" title="' + literals.closeTournamentInscriptionButton + '" class="buttonSaveTournament btn btn-default col-lg-6">' + literals.closeTournamentInscriptionButton + '</button>' + 
             '<button id="cancelCloseInscriptions" title="' + literals.cancelButtonTitle + '" class="buttonSaveTournament btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';
            var modal = _.template(genericModalTemplate, {
                id: this.modalIDCloseTournament,
                title: literals.closeTournamentInscriptionModalTitle,
                content: modalContent,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });

            $(modal).modal();
            $('#' + this.modalIDCloseTournament + ' .modal-dialog').css({'width':'700px'});
            this.setElement(_self.$el.add('#' + this.modalIDCloseTournament));
            return false;
        },
        closeTournamentInscription: function(){
            var _self = this;
            var urlToSend = _self.params.countInscriptions.replace('{idTournament}', _self.tournamentModel.id);
            
            $.ajax({
                type: 'GET',
                url: urlToSend,
                success: function (response) {
                    _self.categoriesTournament = response.inscription;
                    var template = _.template(closeInscriptionTemplate, {
                        data: response.inscription,
                        dataLiterals: literals
                    });
                    _self.renderModalCloseInscriptions(template);
                }
            });
            
        },
        verifyCloseInscriptions: function(){
            var _self = this;
            var numberGroups = _self.getNumberGroups();

            if(!_.isNull(numberGroups)){
                var urlToSend = _self.params.closeInscriptionTournament.replace('{idTournament}', _self.tournamentModel.id);
                $.ajax({
                    type: 'PUT',
                    url: urlToSend,
                    data: JSON.stringify(numberGroups),
                    success: function (response) {
                        alert('aqui hay que recibir los grupos');
                    }
                });
            }
            else{
                alert(literals.errorNumberGroups);
            }
        },
        getNumberGroups: function(){
            var _self = this;
            var numGroups = {};
            var fail = false;
            _.each(_self.categoriesTournament.category, function(category, catKey){
                var catName = catKey.replace(' ', '_');
                var numGroupValue = $('.' + catName + 'NumGroup input').val();
                if(!_.isEmpty(numGroupValue)){
                    numGroups[catKey] = numGroupValue;
                }
                else{
                    fail = true;
                }
            });
            if(fail){
                return null;
            }
            else{
                return numGroups;
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
                "tournament" : _self.tournamentModel.id,
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
        }
    });
    return TournamentAdminView;
});