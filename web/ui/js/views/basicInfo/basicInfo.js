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
            if(!_.isUndefined(model)){
                _self.model = model;
                var urlToSend = params.getUserPreferences.replace('{idUser}', model.get('id'));
                $.ajax({
                    type: 'GET',
                    async: false,
                    url: urlToSend,
                    success: function (response) {
                        _self.userPreferences = response.userPreference;
                    }
                });
            }
        },
        render: function() {
            this.setElement(this.el);
            var _self = this;
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
            _self.modal = _self.renderModal();
            _self.$el.append(_self.modal);
        },
        dataRender: function(){
            var _self = this;
            var dataRender = [
                {
                    'key': 'email',
                    'text': 'Email',
                    'data': this.model.get('email'),
                    'visible': _self.userPreferences.email,
                    'editable': true
                },
                {
                    'key': 'name',
                    'text': 'Name',
                    'data': this.model.get('name'),
                    'visible': _self.userPreferences.name,
                    'editable': true
                },
                {
                    'key': 'lastName',
                    'text': 'Lastname',
                    'data': this.model.get('lastName'),
                    'visible': _self.userPreferences.lastName,
                    'editable': true
                },
                {
                    'key': 'registrationDate',
                    'text': 'Registration Date',
                    'data': new Date(this.model.get('registrationDate').date).toString('d-MM-yyyy'),
                    'visible': _self.userPreferences.registrationDate,
                    'editable': false
                },
                {
                    'key': 'status',
                    'text': 'Status',
                    'data': this.model.get('status').value,
                    'visible': _self.userPreferences.status,
                    'editable': false
                },
                {
                    'key': 'address',
                    'text': 'Address',
                    'data': this.model.get('address'),
                    'visible': _self.userPreferences.address,
                    'editable': true
                },
                {
                    'key': 'city',
                    'text': 'City',
                    'data': this.model.get('city'),
                    'visible': _self.userPreferences.city,
                    'editable': true
                },
                {
                    'key': 'country',
                    'text': 'Country',
                    'data': this.model.get('country'),
                    'visible': _self.userPreferences.country,
                    'editable': true
                },
                {
                    'key': 'cp',
                    'text': 'Postal Code',
                    'data': this.model.get('cp'),
                    'visible': _self.userPreferences.cp,
                    'editable': true
                },
                {
                    'key': 'firstPhone',
                    'text': 'First Phone',
                    'data': this.model.get('firstPhone'),
                    'visible': _self.userPreferences.firstPhone,
                    'editable': true
                },
                {
                    'key': 'gameLevel',
                    'text': 'Game Level',
                    'data': this.model.get('gameLevel'),
                    'visible': _self.userPreferences.gameLevel,
                    'editable': false
                },
                {
                    'key': 'secondPhone',
                    'text': 'Second Phone',
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
            $("#" + _self.modalID).modal();
            $("#" + _self.modalID).addClass('in');
            //$('#' + this.modalID).modal('toggle');
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
                Backbone.emulateHTTP = true;
                _self.model.save();
                $('#' + this.modalID).modal('hide');
                $("#" + _self.modalID).removeClass('in');
                _self.setElement(_self.$el.not('#' + this.modalID));
                this.render();
            }
        },
        cancelProfile: function(){
            var _self = this;
            //$('#' + this.modalID).modal('hide');
            //$("#" + _self.modalID).removeClass('in');
            _self.setElement(_self.$el.not('#' + this.modalID));
            this.render();
        }
    });
    return BasicInfoView;
});