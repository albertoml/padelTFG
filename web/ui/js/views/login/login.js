define([
    'backbone', 
    'underscore',
    'text!templates/login/login.html',
    'i18n!nls/loginLiterals.js'], function(Backbone, _, loginTemplate, literals) {
 
    var LoginView = Backbone.View.extend({
        el: '.wrapperHome',
        events: {
            'click button[name="sendLogin"]':'sendLogin',
            'keypress':'keyPressLogin'
        },
        initialize: function(urls){
            this.sendLoginURL = urls.sendLoginURL;
        },
        render: function() {
            var _self = this;
            var template = _.template(loginTemplate, {
                data: literals
            });
            _self.$el.html(template);
        },
        keyPressLogin: function(e){
            if(e.which == 13) {
                this.sendLogin();
            }
        },
        sendLogin: function(){
            var email = $('input[type="email"]').val();
            var password = $('input[type="password"]').val();
            var urlToSend = this.sendLoginURL.replace('{email}', email);
            var urlToSend = urlToSend.replace('{password}', password);
            $.ajax({
                type: 'GET',
                url: urlToSend,
                success: function (response) {
                    localStorage.setItem('idUser', response.user.user.id);
                    $('.common').trigger('showSuccesAlert', [literals.successMessageText]);
                },
                error: function (msg) {
                    $('.common').trigger('showErrorAlert', [msg.responseText]);
                }
            });
        }    
    });
    return LoginView;
});