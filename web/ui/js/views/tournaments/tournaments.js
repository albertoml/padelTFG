define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html'], function(Backbone, _, DataTableTemplate) {
 
    var TournamentsView = Backbone.View.extend({
        el: '.tournaments',
        events: {
            
        },
        initialize: function(urls){
            
        },
        render: function() {
            var _self = this;
            var template = _.template(DataTableTemplate, {
                tableId : 'tournamentsTable'
            });
            _self.$el.html(template);
            $('#tournamentsTable').dataTable();
        }    
    });
    return TournamentsView;
});