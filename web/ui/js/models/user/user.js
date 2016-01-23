define(['backbone'], function(Backbone) {

	var UserModel = Backbone.Model.extend({
	    urlRoot: 'http://localhost:8000/api/user',
	    initialize: function(urls){
	        
	    }  
	});
	return UserModel;
});