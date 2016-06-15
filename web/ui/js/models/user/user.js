define(['backbone', 'i18n!nls/homeLiterals.js'], function(Backbone, l) {

	var UserModel = Backbone.Model.extend({

	    initialize: function(){
	    	this.urlRoot = l.host + '/' + l.apiName + '/user';   
	    }  
	});
	return UserModel;
});