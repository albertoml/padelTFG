define(['backbone', 'i18n!nls/homeLiterals.js'], function(Backbone, l) {

	var AnnotationModel = Backbone.Model.extend({

	    initialize: function(){
	    	this.urlRoot = l.host + '/' + l.apiName + '/annotation'; 
	    }  
	});

	return AnnotationModel;
});