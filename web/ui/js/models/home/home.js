define(['backbone'], function(Backbone) {

	var HomeModel = Backbone.Model.extend({
	    urlRoot: 'http://localhost:8000/api/user',
	    initialize: function(urls){
	        
	    }  
	});
	return HomeModel;
});