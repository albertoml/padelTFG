define(['backbone', 'i18n!nls/homeLiterals.js'], function(Backbone, l) {

	var GameModel = Backbone.Model.extend({

	    initialize: function(){
	    	this.urlRoot = l.host + '/' + l.apiName + '/game'; 
	    }  
	});

	return GameModel;
});