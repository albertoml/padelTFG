define(['backbone'], function(Backbone) {

	var AnnotationModel = Backbone.Model.extend({
	    urlRoot: 'http://localhost:8000/api/annotation',
	    initialize: function(urls){
	        
	    }  
	});

	return AnnotationModel;
});