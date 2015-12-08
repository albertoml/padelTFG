define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html'], function(Backbone, _, DataTableTemplate) {
 
    var AnnotationsView = Backbone.View.extend({
        el: '.annotations',
        events: {
            
        },
        initialize: function(urls){
            
        },
        render: function() {
            var _self = this;
            var template = _.template(DataTableTemplate, {
                tableId : 'annotationsTable'
            });
            _self.$el.html(template);
            $('#annotationsTable').dataTable();
        }    
    });
    return AnnotationsView;
});