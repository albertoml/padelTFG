define(['backbone'], function(Backbone) {

	var TournamentModel = Backbone.Model.extend({
	    urlRoot: 'http://localhost:8000/api/tournament',
	    initialize: function(urls){
	        
	    }  
	});
	return TournamentModel;
});