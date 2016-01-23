define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, DataTableTemplate, literals) {
 
    var InscriptionsView = Backbone.View.extend({
        el: '.inscriptions',
        events: {
            'click button[id="viewObservations"]' : 'viewObservations',
            'refreshSection' : 'render',
            'click button[id="deleteInscription"]' : 'deleteInscription'
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
                        return data;
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
                        var disabled = "";
                        if(!full.hasObservations){
                            disabled = "disabled";
                        }
                        var button = "<button " + disabled + " id='viewObservations' name=" + full.id + ">" + literals.viewObservation + "</button>";
                        button += "<button title=" + literals.deleteInscription + " id='deleteInscription' name=" + full.id + " class='deleteButton'><i class='fa fa-trash-o'></i></button>";
                        return button;
                    }
                }],
                aaData: _self.inscriptions
            });
        },
        viewObservations: function(){

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
        }    
    });
    return InscriptionsView;
});