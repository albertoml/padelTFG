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
		'datejs': 'vendor/datejs/date',
		'jquery-modal': 'vendor/jquery-modal/jquery.modal'
	},
	shim: {
        'jquery-modal': {
            deps: ['jquery']
        }
    }
});
 
require(['backbone', 'views/login/login', 'views/common/common', 'views/home/home', 'dataTables', 'datejs', 'jquery-modal'], 
	function(Backbone, LoginView, CommonView, HomeView) {

	var host = 'http://localhost:8000';
	
	var params = {
		'host' : 'http://localhost:8000',
		'sendLoginURL' : host + '/api/user/login/{email}/{password}',
		'getUserPreferences' : host + '/api/userPreference/{idUser}'
	};
	var commonView = new CommonView(params);
	commonView.render();
	var Router = Backbone.Router.extend({
		routes: {
			'': 'login',
			'home': 'home'
		},
		login: function(){
			var login = new LoginView(params);
			login.render();
		},
		home: function(){
			var home = new HomeView(params);
			home.render();
		}
	});
	var route = new Router();
 
	Backbone.history.start();
});