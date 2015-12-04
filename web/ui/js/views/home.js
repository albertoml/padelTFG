define([
    'backbone', 
    'underscore',
    'text!templates/login/login.html',
    'i18n!nls/loginLiterals.js'], function(Backbone, _, loginTemplate, literals) {
 
    var HomeView = Backbone.View.extend({
        el: '.page-content',
        events: {
            'click button[name="sendLogin"]':'sendLogin',
            'keypress':'keyPressLogin'
        },
        initialize: function(){
            this.sendLoginURL = "http://localhost:8000/api/user/login/{email}/{password}";
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
                    alert('hecho');
                },
                error: function (msg) {
                    alert('mal');
                }
            });
        }    
    });
    return HomeView;
});