define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html'], function(Backbone, _, DataTableTemplate) {
 
    var PairsView = Backbone.View.extend({
        el: '.pairs',
        events: {
            
        },
        initialize: function(urls){
            
        },
        render: function() {
            var _self = this;
            var template = _.template(DataTableTemplate, {
                tableId : 'pairsTable'
            });
            _self.$el.html(template);
            $('#pairsTable').dataTable();
        }    
    });
    return PairsView;
});