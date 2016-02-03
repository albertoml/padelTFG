define([
    'backbone', 
    'underscore',
    'models/user/user',
    'models/userRole/userRole',
    'i18n!nls/homeLiterals.js',
    'views/basicInfo/basicInfo',
    'views/annotations/annotations',
    'views/tournaments/tournaments',
    'views/inscriptions/inscriptions',
    'views/games/games',
    'views/pairs/pairs',
    'text!templates/home/section.html',
    'text!templates/home/listTree.html',
    'text!templates/home/listTreeAdmin.html',
    'text!templates/home/pageContent.html'], 
    function(Backbone, _, UserModel, UserRoleModel, Literals, BasicInfoView, AnnotationsView, TournamentsView, InscriptionsView, GamesView, PairsView, SectionTemplate, ListTreeTemplate, ListTreeAdmin, PageContentTemplate) {

    var HomeView = Backbone.View.extend({
        el: '.wrapperHome',
        events: {
        },
        initialize: function(params){
            this.sections = Literals.sections;
            this.model = {};
            this.params = params;
        },
        render: function() {
            var _self = this;
            _self.$el.html('');
            var template = _.template(ListTreeTemplate, {
                sections: _self.sections
            });
            _self.$el.append(template);
            var template = _.template(PageContentTemplate, {
            });
            _self.$el.append(template);
            _.each(this.sections, function(section){
                var template = _.template(SectionTemplate, {
                    section: section
                });
                _self.$('.content-home').append(template);
            });
            _self.model = _self.getModel();
        },
        renderJSForListTree: function(){
            var stickyNavTop = $('.sidebar-nav').offset().top;

            var stickyNav = function(){
                var scrollTop = $(window).scrollTop();

                if (scrollTop > stickyNavTop) {
                    $('.sidebar-nav').addClass('isStuck');
                } else {
                    $('.sidebar-nav').removeClass('isStuck');
                }
            };
            stickyNav();
            $(window).scroll(function() {
                stickyNav();
            });
        },
        getModel: function(){
            var _self = this;
            var idUser = localStorage.getItem('idUser');
            var userModel = new UserModel({id: idUser});
            userModel.fetch({
                success: function(model){
                    _self.userModel = model;
                    _self.renderSections();
                }
            });
            var userRoleModel = new UserRoleModel({id: idUser});
            userRoleModel.fetch({
                success: function(modelAdmin){
                    if(!_.isEmpty(modelAdmin.get('tournament'))){
                        _self.renderAdminTreePanel(modelAdmin.get('tournament')); 
                        _self.renderAdminSections(modelAdmin.get('tournament'));        
                    }         
                }
            });
        },
        renderSections: function(){
            var basicInfo = new BasicInfoView(this.userModel, this.params);
            basicInfo.render();
            var annotations = new AnnotationsView(this.userModel, this.params);
            annotations.render();
            var tournaments = new TournamentsView(this.userModel, this.params);
            tournaments.render();
            var inscriptions = new InscriptionsView(this.userModel, this.params);
            inscriptions.render();
            var games = new GamesView(this.userModel, this.params);
            games.render();
            var pairs = new PairsView(this.userModel, this.params);
            pairs.render();
            this.renderJSForListTree();
        },
        renderAdminTreePanel: function(tournaments){
            var template = _.template(ListTreeAdmin, {
                tournaments: tournaments,
                literals: Literals
            });
            $('#menu').append(template);
        },
        renderAdminSections: function(tournaments){
            var template = _.template(SectionTemplate, {

            });
        }
    });
    return HomeView;
});