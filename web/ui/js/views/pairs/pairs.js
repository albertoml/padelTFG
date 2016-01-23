define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, DataTableTemplate, literals) {
 
    var PairsView = Backbone.View.extend({
        el: '.pairs',
        events: {
            'refreshSection' : 'render'
        },
        initialize: function(model, params){
            var _self = this;
            this.params = params;
            _self.model = model;         
        },
        getData: function(){
            var _self = this;
            var url = this.params.getPairsByUser.replace('{idUser}', this.model.id)
            $.ajax({
                type: 'GET',
                async: false,
                url: url,
                success: function (response) {
                    _self.pairs = response.pair;
                }
            });
        },
        render: function() {
            var _self = this;
            this.getData();
            var template = _.template(DataTableTemplate, {
                tableId : 'pairsTable'
            });
            _self.$el.html(template);
            $('#pairsTable').dataTable({
                'search': false,
                'number': false,
                'modelid': 'pair',
                'bAutoWidth': false,
                'identifier': 'identifier',
                'bSort': false,
                'aoColumns': [{
                    'sTitle': literals.pairsFields.pair,
                    'mData': '',
                    'mRender': function (data, type, full) {
                        if(full.user1.name == _self.model.get('name')){
                            return full.user2.name;
                        }
                        else{
                            return full.user1.name;
                        }
                    }
                }],
                aaData: _self.pairs
            });
        }   
    });
    return PairsView;
});