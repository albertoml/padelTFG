require.config({  
	paths: {
		'jquery': 'vendor/jquery/src/jquery',
		'underscore': 'vendor/underscore-amd/underscore',
		'backbone': 'vendor/backbone-amd/backbone',
		'dataTables': 'vendor/datatables/media/js/jquery.dataTables',
		'less': 'vendor/less/dist/less',
		'caret': 'vendor/jquery-tag-editor/jquery.caret.min',
		'tagEditor': 'vendor/jquery-tag-editor/jquery.tag-editor.min',
		'colorpicker': 'vendor/colorpicker/iColorPicker',
		'jquery-tableTools': 'vendor/datatables-tabletools/src/TableTools',
		'text' : 'vendor/requirejs-text/text',
		'templates': '../templates',
		'nls': '../nls',
		'i18n': '../vendor/i18n',
		'datejs': 'vendor/datejs/date',
		'bootstrap': '../styles/bootstrap-3.3.5/js/bootstrap.min',
		'bootstrap-modal': 'vendor/bootstrap-modal/js/bootstrap-modal',
		'bootstrap-modal-manager': 'vendor/bootstrap-modal/js/bootstrap-modalmanager',
		'select2': 'vendor/select2/dist/js/select2.full',
		'datepicker': 'vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min',
		'bs-editable': 'vendor/bootstrap-editable/src/js/bootstrap-editable',
		'smint-jquery': 'vendor/smint-jquery/js/jquery.smint',
		'moment': 'vendor/moment/min/moment.min',
		'fullcalendar': 'vendor/fullcalendar/dist/fullcalendar.min',
		'fullcalendar-scheduler': 'vendor/fullcalendar-scheduler/dist/scheduler.min'
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
        },
        'datepicker': {
        	deps: ['jquery', 'bootstrap']
        },
        'bs-editable': {
        	deps: ['jquery', 'bootstrap']
        },
        'smint-jquery': {
        	deps: ['jquery']
        },
        'fullcalendar': {
        	deps: ['jquery', 'bootstrap', 'moment']
        },
        'fullcalendar-scheduler': {
        	deps: ['fullcalendar']
        },
        'caret':{
        	deps: ['jquery']
        },
        'tagEditor':{
        	deps: ['jquery', 'caret']
        },
        'colorpicker':{
        	deps: ['jquery', 'bootstrap']
        }

    },
    config: {
        //Set the config for the i18n
        i18n: {
            locale: localStorage.locale || 'root'
        }
    }
});
 
require(['backbone', 'views/login/login', 'views/common/common', 'views/home/home', 'dataTables', 'less', 
	'jquery-tableTools', 'datejs', 'select2','bootstrap', 'bootstrap-modal-manager', 'bootstrap-modal', 
	'datepicker', 'bs-editable', 'smint-jquery', 'moment', 'fullcalendar', 'fullcalendar-scheduler', 'tagEditor', 'colorpicker'], 
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
		'hostLocal' : 'http://localhost',
		'apiName' : 'api',
		'sendLoginURL' : host + '/api/user/login/{email}/{password}',
		'getUserPreferences' : host + '/api/userPreference/{idUser}',
		'getUserRoles' : host + '/api/user/roles/{idUser}',
		'getTournamentsAdmin' : host + '/api/tournament/admin/{idUser}',
		'getCategories' : host + '/api/category/tournament/{idTournament}',
		'getObservations' : host + '/api/observation/inscription/{idInscription}',
		'getInscriptionsByUser' : host + '/api/inscription/user/{idUser}',
		'getInscriptionsByTournament' : host + '/api/inscription/tournament/{idTournament}',
		'getAnnotationsByUser' : host + '/api/annotation/user/{idUser}',
		'getGamesByUser' : host + '/api/game/user/{idUser}',
		'getGamesByTournament' : host + '/api/game/tournament/{idTournament}/{isDraw}',
		'getPairsByUser' : host + '/api/pair/user/{idUser}',
		'inscriptionAdminURL' : host + '/api/inscription',
		'userAdminURL' : host + '/api/user',
		'gameAdminURL' : host + '/api/game',
		'pairAdminURL' : host + '/api/pair',
		'scheduleAdminURL' : host + '/api/schedule',
		'observationAdminURL' : host + '/api/observation',
		'tournamentAdminURL' : host + '/api/tournament',
		'groupAdminURL' : host + '/api/group',
		'searchUser' : host + '/api/user/search',
		'getPair2Id' : host + '/api/pair/user/{idUser1}/{idUser2}',
		'saveObservations' : host + '/api/observation/all',
		'getUserGenders' : host + '/api/util/userGender',
		'getGenders' : host + '/api/util/gender',
		'startTournament' : host + '/api/tournament/startTournament/{idTournament}',
		'closeInscriptionTournament' : host + '/api/tournament/closeInscription/{idTournament}',
		'closeGroupsTournament' : host + '/api/tournament/closeGroups/{idTournament}',
		'createDraws' : host + '/api/tournament/createDraw/{idTournament}',
		'addCategoryToTournament' : host + '/api/category/tournament/{idTournament}',
		'getInscriptionsByGroupForATournament' : host + '/api/inscription/group/tournament/{idTournament}',
		'countInscriptions' : host + '/api/inscription/tournamentCount/{idTournament}',
		'checkAndCreatePairsByUsers' : host + '/api/pair/checkAndCreatePairsByUsers',
		'saveGroupsForATournament' : host + '/api/group/tournament',
		'doMatchsForATournament' : host + '/api/game/tournament'
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