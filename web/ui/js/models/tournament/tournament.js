define(['backbone', 'i18n!nls/homeLiterals.js'], function(Backbone, l) {

	var TournamentModel = Backbone.Model.extend({

	    initialize: function(){
	    	this.urlRoot = l.host + '/' + l.apiName + '/tournament'; 
	    }  
	});
	return TournamentModel;
});