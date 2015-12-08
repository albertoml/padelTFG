require.config({  
	paths: {
		'jquery': 'vendor/jquery/src/jquery',
		'underscore': 'vendor/underscore-amd/underscore',
		'backbone': 'vendor/backbone-amd/backbone',
		'dataTables': 'vendor/DataTables/media/js/jquery.dataTables',
		'text' : 'vendor/requirejs-text/text',
		'templates': '../templates',
		'nls': '../nls',
		'i18n': '../vendor/i18n',
		'datejs': 'vendor/datejs/date'
	}
});
 
require(['backbone', 'views/login/login', 'views/common/common', 'views/home/home', 'dataTables', 'datejs'], 
	function(Backbone, LoginView, CommonView, HomeView) {

	var host = 'http://localhost:8000';
	var urls = {
		'sendLoginURL' : host + '/api/user/login/{email}/{password}'
	};
	var commonView = new CommonView();
	commonView.render();
	var Router = Backbone.Router.extend({
		routes: {
			'': 'login',
			'home': 'home'
		},
		login: function(){
			var login = new LoginView(urls);
			login.render();
		},
		home: function(){
			var home = new HomeView(urls);
			home.render();
		}
	});
	var route = new Router();
 
	Backbone.history.start();
});