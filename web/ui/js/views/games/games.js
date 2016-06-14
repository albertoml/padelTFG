define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html',
    'text!templates/games/score.html',
    'text!templates/common/genericModal.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, DataTableTemplate, ScoreTemplate, genericModalTemplate, literals) {
 
    var GamesView = Backbone.View.extend({
        el: '.games',
        events: {
            'click button[id="changeGame"]' : 'changeGame',
            'click button[id="addScore"]' : 'addScore',
            'click button[id="saveScore"]' : 'saveScore'
        },
        initialize: function(model, params){
            var _self = this;
            _self.params = params;
            _self.model = model;
        },
        getData: function(){      
            var _self = this;          
            var url = this.params.getGamesByUser.replace('{idUser}', this.model.id)
            $.ajax({
                type: 'GET',
                async: false,
                url: url,
                success: function (response) {
                    _self.games = response.game;
                }
            });
        },
        render: function() {
            var _self = this;
            this.getData();
            var template = _.template(DataTableTemplate, {
                tableId : 'gamesTable'
            });
            _self.$el.html(template);
            $('#gamesTable').dataTable({
                'search': false,
                'number': false,
                'modelid': 'game',
                'bAutoWidth': false,
                'identifier': 'identifier',
                'bSort': false,
                'aoColumns': [{
                    'sTitle': literals.gamesFields.gameDate,
                    'mData': 'startDate',
                    'mRender': function (data, type, full) {
                        if(!_.isNull(data) && !_.isUndefined(data)){
                                return data.replace('T', '--')
                        }
                        else{
                            return literals.gamesFields.DateNotAvailable;
                        }
                    }
                }, {
                    'sTitle': literals.gamesFields.tournament,
                    'mData': 'tournament',
                    'mRender': function (data, type, full) {
                        return data.name;
                    }
                }, {
                    'sTitle': literals.gamesFields.status,
                    'mData': 'status',
                    'mRender': function (data, type, full) {
                        if(!_.isUndefined(data) && !_.isNull(data)){
                            return data.value
                        }
                        else{
                            return '';
                        }
                    }
                }, {
                    'sTitle': literals.gamesFields.score,
                    'mData': 'score',
                    'mRender': function (data, type, full) {
                        if(!_.isUndefined(data) && !_.isNull(data) && !_.isEmpty(data)){
                            return data;
                        }
                        else{
                            return literals.gamesFields.GameNotPlayed;
                        }
                    }
                }, {
                    'sTitle': literals.gamesFields.options,
                    'mRender': function (data, type, full) {
                        var button = "";
                        button += "<button alt=" + literals.proposeGameChange + " id='changeGame' name=" + full.id + " class='deleteButton btn btn-default'>" + literals.proposeGameChange + "</button>";    
                        if(!_.isUndefined(full.score) && !_.isNull(full.score) && !_.isEmpty(full.score)){
                            button += "<button method='modify' alt=" + literals.modifyScore + " id='addScore' name=" + full.id + " class='deleteButton btn btn-default'>" + literals.modifyScore + "</button>";
                        }
                        else{
                            button += "<button method='add' alt=" + literals.addScore + " id='addScore' name=" + full.id + " class='deleteButton btn btn-default'>" + literals.addScore + "</button>";
                        }

                        return button;
                    }
                }],
                aaData: _self.games
            });
        },
        changeGame: function(e){
            alert('NOT IMPLEMENTED YET');
        },
        addScore: function(e){
            var _self = this;
            var idGame = e.target.name; 
            var method = $(e.target).attr('method');
            var gameToReturn = {};
            _.each(_self.games, function(game){
                if(game.id == idGame){
                    gameToReturn = game;
                }
            })
            _self.showScoreModal(gameToReturn, method);
        },
        showScoreModal: function(game, method){
            var _self = this;
            var modal = _self.renderScoreModal(game);
            
            $(modal).modal();
            $('#' + this.modalID + ' .modal-dialog').css({'width':'600px'});
            this.setElement(_self.$el.add('#' + this.modalID));

            $('.selectScore').select2({
                data: literals.scoreSelectOptions
            });
            if(method == 'modify'){
                var results = _self.parseScore(game.score);
                $('#set1Pair1').val(results[0]).change();
                $('#set1Pair2').val(results[1]).change();
                $('#set2Pair1').val(results[2]).change();
                $('#set2Pair2').val(results[3]).change();
                $('#set3Pair1').val(results[4]).change();
                $('#set3Pair2').val(results[5]).change();
            }
        },
        renderScoreModal: function(game){
            var _self = this;
            this.modalID = 'myScoreModal';
            var buttons = "";
            var modal = "";
            buttons += '<button id="cancelScore" title="' + literals.cancelButtonTitle + '" class="buttonInscription btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';
            buttons += '<button name="' + game.id + '" id="saveScore" title="' + literals.saveButtonTitle + '" class="buttonObservations btn btn-default col-lg-6">' + literals.saveButtonTitle + '</button>';
            
            var template = _.template(ScoreTemplate, {
                dataLiterals : literals,
                content : game
            });

            modal = _.template(genericModalTemplate, {
                id: this.modalID,
                title: literals.modalTitleScore,
                content: template,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });
            return modal;
        },
        parseScore: function(scoreString){
            var setsVars = [];
            _.each(scoreString, function(c){
                if(c != '/' && c != ' '){
                    setsVars.push(parseInt(c));
                }
            })
            if(setsVars.length == 4){
                setsVars.push(-1);
                setsVars.push(-1);
            }
            return setsVars;
        },
        checkValidScore: function(){

            var setsPair1 = [$('#set1Pair1').val(), $('#set2Pair1').val(), $('#set3Pair1').val()];
            var setsPair2 = [$('#set1Pair2').val(), $('#set2Pair2').val(), $('#set3Pair2').val()];

            if(setsPair1[2] == -1 && setsPair2[2] == -1){
                var setPlayed = 2;
                var correctPlayed = 2;
            }
            else{
                var setPlayed = 3;
                var correctPlayed = 3;
            }
            var setWonPair1 = 0;
            var setWonPair2 = 0;
            var minimunSetsForWonMatch = 2;

            var scoreParsed = "";

            for(var i = 0; i < setPlayed; i++){
                if(setsPair1[i] > setsPair2[i]){
                    if(setsPair1[i] == 7 && (setsPair2[i] == 5 || setsPair2[i] == 6)){
                        setWonPair1 ++;
                        correctPlayed --;
                        scoreParsed += setsPair1[i] + '/' + setsPair2[i] + " "; 
                    }
                    else if(setsPair1[i] == 6 && setsPair2[i] >= 0 && setsPair2[i] <= 4){
                        setWonPair1 ++;
                        correctPlayed --;
                        scoreParsed += setsPair1[i] + '/' + setsPair2[i] + " ";
                    }
                }
                else{
                    if(setsPair2[i] == 7 && (setsPair1[i] == 5 || setsPair1[i] == 6)){
                        setWonPair2 ++;
                        correctPlayed --;
                        scoreParsed += setsPair1[i] + '/' + setsPair2[i] + " ";
                    }
                    else if(setsPair2[i] == 6 && setsPair1[i] >= 0 && setsPair1[i] <= 4){
                        setWonPair2 ++;
                        correctPlayed --;
                        scoreParsed += setsPair1[i] + '/' + setsPair2[i] + " ";
                    }
                }
            }

            if(setWonPair1 == minimunSetsForWonMatch && correctPlayed == 0){
                var score = {
                    status: literals.gameStatus.WonPair1,
                    score: scoreParsed.trim()
                };
                return score;
            }
            else if(setWonPair2 == minimunSetsForWonMatch && correctPlayed == 0){
                var score = {
                    status: literals.gameStatus.WonPair2,
                    score: scoreParsed.trim()
                };
                return score;
            }
            else{
                return null;
            }
        },
        saveScore: function(e){
            var _self = this;
            var idGame = e.target.name;
            var score = _self.checkValidScore();
            if(score != null){
                $.ajax({
                    type: 'PUT',
                    url: _self.params.gameAdminURL + '/' + idGame,
                    data: JSON.stringify(score),
                    success: function (response) {
                        $('.common').trigger('showSuccesAlert', [literals.successMessageSaveScore]);
                        _self.render();
                        $('#' + _self.modalID).modal('hide');
                    },
                    error: function(msg){
                        $('.common').trigger('showErrorAlert', [msg.responseJSON.error]);
                    }
                });
            }
            else{
                alert(literals.scoreIncorrect);
            }
        }
    });
    return GamesView;
});