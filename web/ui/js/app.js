require.config({  
	paths: {
		'jquery': 'vendor/jquery/src/jquery',
		'underscore': 'vendor/underscore-amd/underscore',
		'backbone': 'vendor/backbone-amd/backbone',
		'text' : 'vendor/requirejs-text/text',
		'templates': '../templates',
		'nls': '../nls',
		'i18n': '../vendor/i18n'
	}
});
 
require(['backbone', 'views/home'], function(Backbone, HomeView) {

	var home = new HomeView();
 
	var Router = Backbone.Router.extend({
		routes: {
			'': 'home'
		}
	});
 
	var route = new Router();
	route.on('route:home', function() {
		home.render();
	});
 
	Backbone.history.start();
});