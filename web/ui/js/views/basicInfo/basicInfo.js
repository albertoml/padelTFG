define([
    'backbone', 
    'underscore',
    'text!templates/basicInfo/basicInfo.html'], function(Backbone, _, BasicInfoTemplate) {
 
    var BasicInfoView = Backbone.View.extend({
        el: '.basicInfo',
        events: {
            
        },
        initialize: function(model){
            this.model = model;  
        },
        render: function() {
            var _self = this;
            var dataTemplate = _self.dataRender();
            var template = _.template(BasicInfoTemplate, {
                listId : 'basicInfoTable',
                dataTemplate : dataTemplate
            });
            _self.$el.html(template);
        },
        dataRender: function(){
            var _self = this;
            var dataRender = {
                'col1': [
                    {
                        'key': 'email',
                        'text': 'Email',
                        'data': this.model.get('email')
                    },
                    {
                        'key': 'name',
                        'text': 'Name',
                        'data': this.model.get('name')
                    },
                    {
                        'key': 'lastName',
                        'text': 'Lastname',
                        'data': this.model.get('lastName')
                    }
                ],
                'col2': [
                    {
                        'key': 'registrationDate',
                        'text': 'Registration Date',
                        'data': new Date(this.model.get('registrationDate').date).toString('d-MM-yyyy')
                    },
                    {
                        'key': 'status',
                        'text': 'Status',
                        'data': this.model.get('status').value
                    }
                ]
            };
            return dataRender;
        }    
    });
    return BasicInfoView;
});