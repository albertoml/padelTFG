define([
    'backbone', 
    'underscore',
    'text!templates/login/login.html',
    'text!templates/login/registerUser.html',
    'i18n!nls/loginLiterals.js'], function(Backbone, _, loginTemplate, registerUserTemplate, literals) {
 
    var LoginView = Backbone.View.extend({
        el: '.wrapperHome',
        events: {
            'click button[name="sendLogin"]':'sendLogin',
            'click button[name="sendRegister"]':'sendRegister',
            'keypress':'keyPressLogin'
        },
        initialize: function(params){
            this.sendLoginURL = params.sendLoginURL;
            this.sendResgisterURL = params.userAdminURL;
            this.params = params;
        },
        render: function() {
            var _self = this;
            var template = _.template(loginTemplate, {
                data: literals
            });
            _self.$el.html(template);
        },
        renderRegisterUser: function(){
            var _self = this;
            var genders = _self.getGenders();
            var template = _.template(registerUserTemplate, {
                data: literals
            });
            _self.$el.html(template);
            $("#gender").select2({
                data: genders
            });
        },
        getGenders: function(){
            var _self = this;
            var genders = [];
            $.ajax({
                type: 'GET',
                async: false,
                url: _self.params.getUserGenders,
                success: function (response) {
                    _.each(response.genders, function(g, index){
                        var gen = {
                            'id' : g,
                            'text' : g
                        }
                        genders.push(gen);
                    });
                }
            });
            return genders;
        },
        keyPressLogin: function(e){
            if(e.which == 13) {
                if($('button[name="sendLogin"]').length != 0){
                    this.sendLogin(e);
                }
                else{
                    this.sendRegister(e);
                }
            }
        },
        sendLogin: function(e){
            e.stopPropagation();
            e.stopImmediatePropagation();
            var _self = this;
            var email = $('input[type="email"]').val();
            var password = $('input[type="password"]').val();
            if(_.isEmpty(email) || _.isEmpty(password)){
                $('.common').trigger('showErrorAlert', ['There are empty fields']);
            }
            else{
                var urlToSend = this.sendLoginURL.replace('{email}', email);
                urlToSend = urlToSend.replace('{password}', password);
                $.ajax({
                    type: 'GET',
                    url: urlToSend,
                    success: function (response) {
                        localStorage.setItem('idUser', response.user);
                        $('.login-form').html('');
                        $('.common').trigger('showSuccesAlert', [literals.successMessageTextLogin]);
                        setTimeout(function(){
                            $('.active').show();
                            window.location.replace(_self.params.hostLocal + '#home');
                        }, 2000);
                    },
                    error: function (msg) {
                        $('.common').trigger('showErrorAlert', [msg.responseText]);
                    }
                });
            }
        },
        sendRegister: function(e){
            var _self = this;
            e.stopPropagation();
            e.stopImmediatePropagation();
            var name = $('input[name="name"]').val();
            var lastName = $('input[name="lastName"]').val();
            var email = $('input[type="email"]').val();
            var password = $('input[type="password"]').val();
            var gender = $('#gender').val();
            if(_.isEmpty(name) || _.isEmpty(lastName) || _.isEmpty(email) || _.isEmpty(password) || gender==literals.placeholderGender){
                $('.common').trigger('showErrorAlert', ['There are empty fields']);
            }
            else{
                var data = {
                    'name': name,
                    'lastName': lastName,
                    'email': email,
                    'password': password,
                    'gender': gender
                };
                var urlToSend = this.sendResgisterURL;
                $.ajax({
                    type: 'POST',
                    data: JSON.stringify(data),
                    url: urlToSend,
                    success: function (response) {
                        localStorage.setItem('idUser', response.user.id);
                        $('.login-form').html('');
                        $('.common').trigger('showSuccesAlert', [literals.successMessageTextRegister]);
                        setTimeout(function(){
                            $('.active').show();
                            window.location.replace(_self.params.hostLocal + '#home');
                        }, 2000);
                    },
                    error: function (msg) {
                        $('.common').trigger('showErrorAlert', [msg.responseText]);
                    }
                });
            }
        }    
    });
    return LoginView;
});