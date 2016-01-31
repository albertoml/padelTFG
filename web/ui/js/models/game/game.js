define(['backbone'], function(Backbone) {

	var GameModel = Backbone.Model.extend({
	    urlRoot: 'http://localhost:8000/api/game',
	    initialize: function(urls){
	        
	    }  
	});

	return GameModel;
});