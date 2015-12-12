define([
    'backbone', 
    'underscore',
    'models/home/home',
    'views/basicInfo/basicInfo',
    'views/annotations/annotations',
    'views/tournaments/tournaments',
    'views/matchs/matchs',
    'views/pairs/pairs',
    'text!templates/home/section.html',
    'text!templates/home/listTree.html',
    'text!templates/home/pageContent.html'], 
    function(Backbone, _, HomeModel, BasicInfoView, AnnotationsView, TournamentsView, MatchsView, PairsView, SectionTemplate, ListTreeTemplate, PageContentTemplate) {

    var HomeView = Backbone.View.extend({
        el: '.wrapperHome',
        events: {
            
        },
        initialize: function(params){
            this.sections = [
            {'key':'basicInfo', 'text':'Basic Information'},
            {'key':'annotations', 'text':'Annotations'},
            {'key':'tournaments', 'text':'Tournaments'},
            {'key':'matchs', 'text':'Matchs'},
            {'key':'pairs', 'text':'My pairs Info'},
            ];
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
            var homeModel = new HomeModel({id: idUser});
            homeModel.fetch({
                success: function(model){
                    _self.model = model;
                    _self.renderSections();
                }
            });
        },
        renderSections: function(){
            var basicInfo = new BasicInfoView(this.model, this.params);
            basicInfo.render();
            var annotations = new AnnotationsView();
            annotations.render();
            var tournaments = new TournamentsView();
            tournaments.render();
            var matchs = new MatchsView();
            matchs.render();
            var pairs = new PairsView();
            pairs.render();
            this.renderJSForListTree();
        }
    });
    return HomeView;
});