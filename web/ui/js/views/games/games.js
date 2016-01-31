define([
    'backbone', 
    'underscore',
    'text!templates/home/dataTable.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, DataTableTemplate, literals) {
 
    var GamesView = Backbone.View.extend({
        el: '.games',
        events: {
            
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
                    'mData': 'date',
                    'mRender': function (data, type, full) {
                        return new Date(data.date).toString('d-MM-yyyy');
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
                    'sTitle': literals.gamesFields.options,
                    'mRender': function (data, type, full) {
                        var button = "<button id='modifyAnnotation' name=" + full.id + "><i class='fa fa-pencil-square-o'></i></button>";
                        button += "<button alt=" + literals.deleteAnnotation + " id='deleteAnnotation' name=" + full.id + " class='deleteButton'><i class='fa fa-trash-o'></i></button>";
                        return button;
                    }
                }],
                aaData: _self.games
            });
        }    
    });
    return GamesView;
});