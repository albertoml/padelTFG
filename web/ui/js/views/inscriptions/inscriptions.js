define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html',
    'text!templates/tournament/observation.html',
    'text!templates/common/genericModal.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, DataTableTemplate, ObservationTemplate, genericModalTemplate, literals) {
 
    var InscriptionsView = Backbone.View.extend({
        el: '.inscriptions',
        events: {
            'click button[id="viewObservations"]' : 'viewObservations',
            'refreshSection' : 'render',
            'click button[id="deleteInscription"]' : 'deleteInscription',
            'click button[id="insertObservation"]': 'addObservation',
            'click button[id="deleteObservation"]': 'deleteObservation',
            'click button[id="saveObservations"]': 'saveObservations'
        },
        initialize: function(model, params){
            var _self = this;
            this.params = params;
            _self.model = model;
        },
        getData: function(){      
            var _self = this;          
            var url = this.params.getInscriptionsByUser.replace('{idUser}', this.model.id)
            $.ajax({
                type: 'GET',
                async: false,
                url: url,
                success: function (response) {
                    _self.inscriptions = response.inscription;
                }
            });
        },
        render: function() {
            var _self = this;
            this.getData();
            var template = _.template(DataTableTemplate, {
                tableId : 'inscriptionsTable'
            });
            _self.$el.html(template);
            $('#inscriptionsTable').dataTable({
                'search': false,
                'number': false,
                'modelid': 'inscription',
                'bAutoWidth': false,
                'identifier': 'identifier',
                'bSort': false,
                'aoColumns': [{
                    'sTitle': literals.inscriptionsFields.pair,
                    'mData': 'pair',
                    'mRender': function (data, type, full) {
                        if(data.user1.name == _self.model.get('name')){
                            return data.user2.name;
                        }
                        else{
                            return data.user1.name;
                        }
                    }
                }, {
                    'sTitle': literals.inscriptionsFields.category,
                    'mData': 'category',
                    'mRender': function (data, type, full) {
                        return data.name;
                    }
                }, {
                    'sTitle': literals.inscriptionsFields.tournament,
                    'mData': 'tournament',
                    'mRender': function (data, type, full) {
                        return data.name;
                    }
                }, {
                    'sTitle': literals.inscriptionsFields.group,
                    'mData': 'group',
                    'mRender': function (data, type, full) {
                        if(!_.isNull(data) && !_.isUndefined(data) && !_.isUndefined(data.name)){
                            return data.name;    
                        }
                        else{
                            return '';
                        }
                        
                    }
                }, {
                    'sTitle': literals.inscriptionsFields.status,
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
                    'sTitle': literals.inscriptionsFields.options,
                    'mRender': function (data, type, full) {
                        var button = "";
                        var observationsReadOnly = "";
                        if(full.tournament.status.value != literals.tournamentStatus.InInscriptionDate && full.tournament.status.value != literals.tournamentStatus.InGroupDate){
                            observationsReadOnly = "readOnly"
                        }
                        button += "<button class='btn btn-default' available='" + observationsReadOnly + "' id='viewObservations' name=" + full.id + ">" + literals.viewObservation + "</button>";
                        if(full.tournament.status.value == literals.tournamentStatus.InInscriptionDate){
                            button += "<button alt=" + literals.deleteInscription + " id='deleteInscription' name=" + full.id + " class='deleteButton btn btn-default'><i class='fa fa-trash-o'></i></button>";
                        }                      
                        return button;
                    }
                }],
                aaData: _self.inscriptions
            });
        },
        viewObservations: function(e){
            var _self = this;
            _self.inscriptionId = e.target.name;
            var observations = _self.getObservations(e.target.name);
            if(observations.length > 0){
                var modal = _self.renderModal(observations, e);
            }
            else{
                var modal = _self.renderModal(null, e);
            }
            
            $(modal).modal();
            $('#' + this.modalID + ' .modal-dialog').css({'width':'600px'});
            this.setElement(_self.$el.add('#' + this.modalID));
        },
        getObservations: function(inscriptionId){
            var _self = this;
            var urlToSend = _self.params.getObservations.replace('{idInscription}', inscriptionId);
            var observations = [];
            $.ajax({
                type: 'GET',
                async: false,
                url: urlToSend,
                success: function (response) {
                    observations = response.observation;
                }
            });
            return observations;
        },
        renderModal: function(observations, event){
            var _self = this;
            this.modalID = 'myObservationsModal';
            var buttons = "";
            var modal = "";
            buttons += '<button id="cancelObservations" title="' + literals.cancelButtonTitle + '" class="buttonInscription btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';
            if(_.isEmpty($(event.target).attr('available'))){
                buttons += '<button id="saveObservations" title="' + literals.saveButtonTitle + '" class="buttonObservations btn btn-default col-lg-6">' + literals.saveButtonTitle + '</button>';
            }
            else if(_.isNull(observations)){
                modal = _.template(genericModalTemplate, {
                    id: this.modalID,
                    title: literals.modalTitleObservation,
                    content: literals.noHaveObservationsAndNotCanInsert,
                    buttons: buttons,
                    onCloseAction: 'closeModal'
                });
                return modal;
            }
            var modalContent = '<div class="col-lg-12" id="observations">';
            _.each(observations, function(obs){
                var template = _.template(ObservationTemplate, {
                    dataLiterals : literals,
                    content : obs
                });
                modalContent += template;
            });
            modalContent += '</div>';
            modalContent += '<div style="margin-top:20px" class="buttons col-lg-12">';
            modalContent += '<button class="btn btn-default" id="insertObservation">' + literals.addObservation + '</button>';
            modalContent += '</div>';


            modal = _.template(genericModalTemplate, {
                id: this.modalID,
                title: literals.modalTitleObservation,
                content: modalContent,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });
            return modal;
        },
        addObservation: function(){
            var _self = this;
            var template = _.template(ObservationTemplate, {
                dataLiterals : literals
            });
            $('#observations').append(template);
            $('.date').last().datepicker({
                format: "dd/mm/yyyy",
                startDate: new Date(),
                language: "en",
                weekStart: 1,
                daysOfWeekHighlighted: "5,6,0",
                autoclose: true
            });
        },
        deleteObservation: function(e){
            if(e.target.tagName == "I"){
                e.target.parentElement.parentElement.parentElement.remove();
            }
            else{
                e.target.parentElement.parentElement.remove();
            }
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
                            _self.render();
                        }
                        else{
                            $('.common').trigger('showErrorAlert', [literals.errorMessageDeleteInscription]);
                        }   
                    }
                });
            }
        },
        saveObservations: function(e){
            var _self = this;
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
            var observationsToSend = {
                "inscription" : _self.inscriptionId,
                "observations" : observations
            };
            $.ajax({
                type: 'POST',
                url: _self.params.saveObservations,
                data: JSON.stringify(observationsToSend),
                success: function (response) {
                    $('.common').trigger('showSuccesAlert', [literals.successMessageTextObservations]);
                    _self.render();
                    $('#' + _self.modalID).modal('hide');
                },
                error: function(msg){
                    $('.common').trigger('showErrorAlert', [msg.responseJSON.error]);
                }
            });
        }    
    });
    return InscriptionsView;
});