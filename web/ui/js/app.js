require.config({  
	paths: {
		'jquery': 'vendor/jquery/src/jquery',
		'underscore': 'vendor/underscore-amd/underscore',
		'backbone': 'vendor/backbone-amd/backbone',
		'dataTables': 'vendor/dataTables/media/js/jquery.dataTables',
		'jquery-tableTools': 'vendor/datatables-tabletools/src/TableTools',
		'text' : 'vendor/requirejs-text/text',
		'templates': '../templates',
		'nls': '../nls',
		'i18n': '../vendor/i18n',
		'datejs': 'vendor/datejs/date',
		'bootstrap': '../styles/bootstrap-3.3.5/js/bootstrap.min',
		'bootstrap-modal': 'vendor/bootstrap-modal/js/bootstrap-modal',
		'bootstrap-modal-manager': 'vendor/bootstrap-modal/js/bootstrap-modalmanager',
		'select2': 'vendor/select2/dist/js/select2.full.min'
	},
	shim: {
        'bootstrap-modal-manager': {
            deps: ['jquery', 'bootstrap']
        },
        'bootstrap-modal': {
            deps: ['jquery', 'bootstrap']
        },
        'jquery-tableTools': {
            deps: ['jquery']
        },
        'bootstrap': {
        	deps: ['jquery']
        },
        'select2': {
        	deps: ['jquery']
        }
    },
    config: {
        //Set the config for the i18n
        i18n: {
            locale: localStorage.locale || 'root'
        }
    }
});
 
require(['backbone', 'views/login/login', 'views/common/common', 'views/home/home', 'dataTables', 'jquery-tableTools', 'datejs', 'select2','bootstrap', 'bootstrap-modal-manager', 'bootstrap-modal'], 
	function(Backbone, LoginView, CommonView, HomeView) {

	TableTools.DEFAULTS.sSwfPath = '/vendor/datatables-tabletools/swf/copy_csv_xls_pdf.swf';
	TableTools.DEFAULTS.aButtons = [{
	        'sExtends': 'copy',
	        'mColumns': 'visible'
	    }, {
	        'sExtends': 'xls',
	        'mColumns': 'visible'
	    }, {
	        'sExtends': 'pdf',
	        'sPdfOrientation': 'landscape',
	        'mColumns': 'visible'
	}];
	$.support.cors = true;

    $.ajaxSetup({
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        cache: false
    });

	var host = 'http://localhost:8000';
	var params = {
		'host' : 'http://localhost:8000',
		'hostLocal' : 'http://localhost:8080',
		'sendLoginURL' : host + '/api/user/login/{email}/{password}',
		'getUserPreferences' : host + '/api/userPreference/{idUser}',
		'getTournaments' : host + '/api/tournament',
		'getCategories' : host + '/api/category/tournament/{idTournament}',
		'getObservations' : host + '/api/observation/inscription/{idInscription}',
		'getInscriptionsByUser' : host + '/api/inscription/user/{idUser}',
		'getAnnotationsByUser' : host + '/api/annotation/user/{idUser}',
		'getGamesByUser' : host + '/api/game/user/{idUser}',
		'getPairsByUser' : host + '/api/pair/user/{idUser}',
		'inscriptionAdminURL' : host + '/api/inscription',
		'userAdminURL' : host + '/api/user',
		'pairAdminURL' : host + '/api/pair',
		'searchUser' : host + '/api/user/search',
		'getPair2Id' : host + '/api/pair/user/{idUser1}/{idUser2}',
		'saveObservations' : host + '/api/observation/all',
		'getUserGenders' : host + '/api/util/userGender'
	};

	var commonView = new CommonView(params);
	commonView.render();
	var Router = Backbone.Router.extend({
		routes: {
			'': 'login',
			'home': 'home',
			'registerUser': 'registerUser'
		},
		login: function(){
			var login = new LoginView(params);
			login.render();
		},
		home: function(){
			var home = new HomeView(params);
			home.render();
		},
		registerUser: function(){
			var login = new LoginView(params);
			login.renderRegisterUser();
		}
	});
	var route = new Router();
 
	Backbone.history.start();
});