define(['backbone', 'i18n!nls/homeLiterals.js'], function(Backbone, l) {

	var PairModel = Backbone.Model.extend({

	    initialize: function(){
	    	this.urlRoot = l.host + '/' + l.apiName + '/pair'; 
	    }
	});
	return PairModel;
});
