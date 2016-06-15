define(['backbone', 'i18n!nls/homeLiterals.js'], function(Backbone, l) {

	var UserRoleModel = Backbone.Model.extend({
		
	    initialize: function(){
	    	this.urlRoot = l.host + '/' + l.apiName + '/userUserRole';  
	    }  
	});
	return UserRoleModel;
});