define([
    'backbone', 
    'underscore',
    'text!templates/common/alertSuccess.html',
    'text!templates/common/alertError.html',
    'text!templates/common/nav.html',
    'i18n!nls/commonLiterals.js',
    'views/home/home'], function(Backbone, _, alertSuccessTemplate, alertErrorTemplate, NavTemplate, literals, HomeView) {
 
    var CommonView = Backbone.View.extend({
        el: '.common',
        events: {
            'showSuccesAlert':'showSuccesAlert',
            'showErrorAlert':'showErrorAlert',
            'click button[id="menu-toggle-2"]':'hideMenu'
        },
        initialize: function(params){
            this.model = {};
            this.params = params;
        },
        render: function(){
            var template = _.template(NavTemplate, {
                nameSite: literals.nameSite
            });
            this.$el.html(template);
            $('.active').hide();
        },
        showSuccesAlert: function(e, successText) {
            var _self = this;
            var template = _.template(alertSuccessTemplate, {
                data: literals,
                msgSuccess: successText
            });
            $('.alertModal').remove();
            _self.$el.append(template);
            e.currentTarget.addEventListener("animationstart", function(){_self.listenerAlertStart(_self, e)}, false);
            e.currentTarget.addEventListener("animationend", function(){_self.listenerAlertEnd(_self, e)}, false);
        },
        showErrorAlert: function(e, errorText) {
            var _self = this;
            var template = _.template(alertErrorTemplate, {
                data: literals,
                msgError: errorText
            });
            $('.alertModal').remove();
            _self.$el.append(template);
        },
        listenerAlertEnd: function(model, e) {
            $('.alertModal').remove();
            e.currentTarget.removeEventListener("animationend", model.listenerAlert, false);
        },
        listenerAlertStart: function(model, e){
            var home = new HomeView(model.params);
            $('.login-form').html('');
            setTimeout(function(){
                $('.active').show();
                home.render();
            }, 2000);
            e.currentTarget.removeEventListener("animationstart", model.listenerAlert, false);
        },
        hideMenu: function(e){
            e.preventDefault();
            $("#wrapper").toggleClass("toggled-2");
            $('#menu ul').hide();
        }  
    });
    return CommonView;
});