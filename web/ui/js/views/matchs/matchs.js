define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html'], function(Backbone, _, DataTableTemplate) {
 
    var MatchsView = Backbone.View.extend({
        el: '.matchs',
        events: {
            
        },
        initialize: function(urls){
            
        },
        render: function() {
            var _self = this;
            var template = _.template(DataTableTemplate, {
                tableId : 'matchsTable'
            });
            _self.$el.html(template);
            $('#matchsTable').dataTable();
        }    
    });
    return MatchsView;
});