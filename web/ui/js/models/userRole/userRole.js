define(['backbone'], function(Backbone) {

	var UserRoleModel = Backbone.Model.extend({
	    urlRoot: 'http://localhost:8000/api/userUserRole',
	    initialize: function(urls){
	        
	    }  
	});
	return UserRoleModel;
});