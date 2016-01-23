define([
    'backbone', 
    'underscore',
    'text!templates/basicInfo/basicInfo.html',
    'text!templates/basicInfo/basicInfoProfile.html',
    'text!templates/common/genericModal.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, BasicInfoTemplate, BasicProfileTemplate, genericModalTemplate, literals) {
 
    var BasicInfoView = Backbone.View.extend({
        el: '.basicInfo',
        events: {
            'click button[id="profileBasicInfo"]' : 'showProfileModal',
            'click button[id="saveProfile"]' : 'saveProfile',
            'click button[id="cancelProfile"]' : 'cancelProfile'
        },
        initialize: function(model, params){
            var _self = this;
            this.params = params;
            this.model = model;
        },
        render: function() {
            var _self = this;
            this.setElement(this.el);
            if(!_.isUndefined(this.model)){
                var urlToSend = this.params.getUserPreferences.replace('{idUser}', this.model.get('id'));
                $.ajax({
                    type: 'GET',
                    async: false,
                    url: urlToSend,
                    success: function (response) {
                        _self.userPreferences = response.userPreference;
                    }
                });
            }
            var dataRender = _self.dataRender();
            var dataTemplate = [];
            _.each(dataRender, function(item){
                if(item.visible == true){
                    dataTemplate.push(item);
                }
            });
            var template = _.template(BasicInfoTemplate, {
                listId : 'basicInfoTable',
                dataTemplate : dataTemplate,
                dataLiterals : literals
            });
            _self.$el.html(template);
        },
        dataRender: function(){
            var _self = this;
            var dataRender = [
                {
                    'key': 'email',
                    'text': literals.fields.email,
                    'data': this.model.get('email'),
                    'visible': _self.userPreferences.email,
                    'editable': true
                },
                {
                    'key': 'name',
                    'text': literals.fields.name,
                    'data': this.model.get('name'),
                    'visible': _self.userPreferences.name,
                    'editable': true
                },
                {
                    'key': 'lastName',
                    'text': literals.fields.lastName,
                    'data': this.model.get('lastName'),
                    'visible': _self.userPreferences.lastName,
                    'editable': true
                },
                {
                    'key': 'registrationDate',
                    'text': literals.fields.registrationDate,
                    'data': new Date(this.model.get('registrationDate').date).toString('d-MM-yyyy'),
                    'visible': _self.userPreferences.registrationDate,
                    'editable': false
                },
                {
                    'key': 'status',
                    'text': literals.fields.status,
                    'data': this.model.get('status').value,
                    'visible': _self.userPreferences.status,
                    'editable': false
                },
                {
                    'key': 'address',
                    'text': literals.fields.address,
                    'data': this.model.get('address'),
                    'visible': _self.userPreferences.address,
                    'editable': true
                },
                {
                    'key': 'city',
                    'text': literals.fields.city,
                    'data': this.model.get('city'),
                    'visible': _self.userPreferences.city,
                    'editable': true
                },
                {
                    'key': 'country',
                    'text': literals.fields.country,
                    'data': this.model.get('country'),
                    'visible': _self.userPreferences.country,
                    'editable': true
                },
                {
                    'key': 'cp',
                    'text': literals.fields.cp,
                    'data': this.model.get('cp'),
                    'visible': _self.userPreferences.cp,
                    'editable': true
                },
                {
                    'key': 'firstPhone',
                    'text': literals.fields.firstPhone,
                    'data': this.model.get('firstPhone'),
                    'visible': _self.userPreferences.firstPhone,
                    'editable': true
                },
                {
                    'key': 'gameLevel',
                    'text': literals.fields.gameLevel,
                    'data': this.model.get('gameLevel'),
                    'visible': _self.userPreferences.gameLevel,
                    'editable': false
                },
                {
                    'key': 'secondPhone',
                    'text': literals.fields.secondPhone,
                    'data': this.model.get('secondPhone'),
                    'visible': _self.userPreferences.secondPhone,
                    'editable': true
                }
            ];
            return dataRender;
        },
        renderModal: function(){
            var _self = this;
            this.modalID = 'myProfileModal';
            var myModal = $(this.modalID);
            var buttons = '<button id="saveProfile" title="' + literals.saveButtonTitle + '" class="buttonBasicInfo btn btn-default col-lg-6">' + literals.saveButtonTitle + '</button>' + 
            '<button id="cancelProfile" title="' + literals.cancelButtonTitle + '" class="buttonBasicInfo btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';
            var modalContent = _self.loadProfileInfo();
            var modal = _.template(genericModalTemplate, {
                id: this.modalID,
                title: literals.modalTitleProfile,
                content: modalContent,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });
            return modal;
        },
        showProfileModal: function(){
            var _self = this;
            var modal = _self.renderModal();
            $(modal).modal();
            $('#' + this.modalID + ' .modal-dialog').css({'width':'800px'});
            this.setElement(_self.$el.add('#' + this.modalID));
        },
        loadProfileInfo: function(){
            var _self = this;
            var dataTemplate = _self.dataRender();
            var template = _.template(BasicProfileTemplate, {
                profileId : 'bodyProfile',
                dataTemplate : dataTemplate,
                dataLiterals : literals
            });
            return template;
        },
        saveProfile: function(){
            var _self = this;
            var result = confirm(literals.confirmSaveProfile);
            if(result){
                var changes = _self.getChanges();
                var changesPreferences = _self.getPreferencesChanges();
                _self.model.save(changes);
                var urlToSend = _self.params.getUserPreferences.replace('{idUser}', _self.model.get('id'));
                $.ajax({
                    type: 'PUT',
                    data: JSON.stringify(changesPreferences),
                    url: urlToSend,
                    success: function (response) {
                        _self.userPreferences = response.userPreference;
                        _self.render();
                    }
                });
            }
            $('#' + this.modalID).modal('hide');
        },
        cancelProfile: function(){
            var _self = this;
            $('#' + this.modalID).modal('hide');
            this.render();
        },
        getChanges: function(){
            var changes = {
                "email": $('.emailProfile input[type="text"]').val(),
                "name": $('.nameProfile input[type="text"]').val(),
                "lastName": $('.lastNameProfile input[type="text"]').val(),
                "address": $('.addressProfile input[type="text"]').val(),
                "city": $('.cityProfile input[type="text"]').val(),
                "country": $('.countryProfile input[type="text"]').val(),
                "cp": $('.cpProfile input[type="text"]').val(),
                "firstPhone": $('.firstPhoneProfile input[type="text"]').val(),
                "secondPhone": $('.secondPhoneProfile input[type="text"]').val()
            };
            return changes;
        },
        getPreferencesChanges: function(){
            var changes = {
                "email": $('.emailProfile input[type="checkbox"]').is(':checked'),
                "name": $('.nameProfile input[type="checkbox"]').is(':checked'),
                "lastName": $('.lastNameProfile input[type="checkbox"]').is(':checked'),
                "registrationDate": $('.registrationDateProfile input[type="checkbox"]').is(':checked'),
                "status": $('.statusProfile input[type="checkbox"]').is(':checked'),
                "address": $('.addressProfile input[type="checkbox"]').is(':checked'),
                "city": $('.cityProfile input[type="checkbox"]').is(':checked'),
                "country": $('.countryProfile input[type="checkbox"]').is(':checked'),
                "cp": $('.cpProfile input[type="checkbox"]').is(':checked'),
                "firstPhone": $('.firstPhoneProfile input[type="checkbox"]').is(':checked'),
                "gameLevel": $('.gameLevelProfile input[type="checkbox"]').is(':checked'),
                "secondPhone": $('.secondPhoneProfile input[type="checkbox"]').is(':checked')
            };
            return changes;
        }
    });
    return BasicInfoView;
});