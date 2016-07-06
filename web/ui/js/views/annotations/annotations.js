define([
    'backbone', 
    'underscore',
    'models/annotation/annotation',
    'text!templates/home/dataTable.html',
    'text!templates/common/genericModal.html',
    'i18n!nls/homeLiterals.js'], function(Backbone, _, AnnotationModel, DataTableTemplate, genericModalTemplate, literals) {
 
    var AnnotationsView = Backbone.View.extend({
        el: '.annotations',
        events: {
            'click button[id="deleteAnnotation"]' : 'deleteAnnotation',
            'click button[id="createAnnotation"]' : 'createAnnotation',
            'click button[id="modifyAnnotation"]' : 'modifyAnnotation',  
            'click button[id="saveAnnotations"]' : 'saveAnnotation',
            'keyup textarea[id="insertTextAnnotation"]' : 'enableSaveButton'
        },
        initialize: function(model, params){
            var _self = this;
            _self.params = params;
            _self.model = model;
        },
        getData: function(){      
            var _self = this;          
            var url = this.params.getAnnotationsByUser.replace('{idUser}', this.model.id)
            $.ajax({
                type: 'GET',
                async: false,
                url: url,
                success: function (response) {
                    _self.annotations = response.annotation;
                }
            });
        },
        render: function() {
            var _self = this;
            this.getData();
            var template = _.template(DataTableTemplate, {
                tableId : 'annotationsTable'
            });
            template += "<button id='createAnnotation' class='col-lg-5 btn btn-default buttonNewObservation'>" + literals.createAnnotation + "</button>";
            _self.$el.html(template);
            $('#annotationsTable').dataTable({
                'search': false,
                'number': false,
                'modelid': 'annotation',
                'bAutoWidth': false,
                'identifier': 'identifier',
                'bSort': false,
                'aoColumns': [{
                    'sTitle': literals.annotationsFields.creationDate,
                    'mData': 'creationDate',
                    'mRender': function (data, type, full) {
                        return new Date(data.date).toString('d-MM-yyyy');
                    }
                }, {
                    'sTitle': literals.annotationsFields.text,
                    'mData': 'text',
                    'mRender': function (data, type, full) {
                        return data;
                    }
                }, {
                    'sTitle': literals.annotationsFields.status,
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
                    'sTitle': literals.annotationsFields.options,
                    'mRender': function (data, type, full) {
                        var button = "<button class='btn btn-default' id='modifyAnnotation' name=" + full.id + "><i class='fa fa-pencil-square-o'></i></button>";
                        button += "<button class='btn btn-default' alt=" + literals.deleteAnnotation + " id='deleteAnnotation' name=" + full.id + " class='deleteButton'><i class='fa fa-trash-o'></i></button>";
                        return button;
                    }
                }],
                aaData: _self.annotations
            });
        },
        createAnnotation: function(e){
            var _self = this;
            var modal = _self.renderModal();
            $(modal).modal();
            $('#' + this.modalID + ' .modal-dialog').css({'width':'600px'});
            this.setElement(_self.$el.add('#' + this.modalID));
        },
        renderModal: function(annotation){
            var _self = this;
            this.modalID = 'myObservationsModal';
            var myModal = $(this.modalID);
            var buttons = '<button disabled=true style="color:#000" id="saveAnnotations" title="' + literals.saveButtonTitle + '" class="buttonAnnotations btn btn-default col-lg-6">' + literals.saveButtonTitle + '</button>' + 
            '<button id="cancelAnnotations" title="' + literals.cancelButtonTitle + '" class="buttonAnnotations btn btn-default" data-dismiss="modal">' + literals.cancelButtonTitle + '</button>';

            var modalContent = '<div class="col-lg-12" id="createAnnotations">';
            if(!_.isUndefined(annotation)){
                modalContent += '<textArea name="' + annotation.id + '" rows="4" class="col-lg-10" id="insertTextAnnotation" placeholder=' + literals.insertAnnotation + '>' + annotation.text;
            }
            else{
                modalContent += '<textArea name="newAnnotation" rows="4" class="col-lg-10" id="insertTextAnnotation" placeholder=' + literals.insertAnnotation + '>';
            }
            modalContent += '</textArea>';
            modalContent += '</div>';

            var modal = _.template(genericModalTemplate, {
                id: this.modalID,
                title: literals.modalTitleAnnotation,
                content: modalContent,
                buttons: buttons,
                onCloseAction: 'closeModal'
            });
            return modal;
        },
        enableSaveButton: function(e){
            if(_.isEmpty($('#insertTextAnnotation').val())){
                $('#saveAnnotations').attr('disabled','disabled');
                $('#saveAnnotations').css({'color':'#000'});
            }
            else{
                $('#saveAnnotations').css({'color':'#fff'});
                $('#saveAnnotations').removeAttr('disabled');
            }
        },
        saveAnnotation: function(e){
            var _self = this;
            if($('#insertTextAnnotation').attr('name') == "newAnnotation"){
                var annotationModel = new AnnotationModel();
                var userDetails = {
                    text: $('#insertTextAnnotation').val(),
                    user: _self.model.get('id')
                };
                annotationModel.save(userDetails, {
                    success: function (annotation) {
                        _self.render();
                        $('#' + _self.modalID).modal('hide');
                    }
                });
            }
            else{
                var annotationModel = new AnnotationModel({id : $('#insertTextAnnotation').attr('name')});
                var userDetails = {
                    text: $('#insertTextAnnotation').val(),
                };
                annotationModel.save(userDetails, {
                    success: function (annotation) {
                        _self.render();
                        $('#' + _self.modalID).modal('hide');
                    }
                });
            }
        },
        modifyAnnotation: function(e){
            var _self = this;
            if(e.target.tagName == "I"){
                var node = e.target.parentElement;
            }
            else{
                var node = e.target;
            }
            var annotationModel = new AnnotationModel({id: node.name});
            annotationModel.fetch({
                success: function(){
                    var modal = _self.renderModal(annotationModel.get('annotation'));
                    $(modal).modal();
                    $('#' + _self.modalID + ' .modal-dialog').css({'width':'600px'});
                    _self.setElement(_self.$el.add('#' + _self.modalID));
                }
            });
        },
        deleteAnnotation: function(e){
            var _self = this;
            if(confirm(literals.confirmDeleteAnnotation)){
                if(e.target.tagName == "I"){
                    var node = e.target.parentElement;
                }
                else{
                    var node = e.target;
                }
                var annotationModel = new AnnotationModel({id: node.name});
                annotationModel.destroy({
                    success: function () {
                        _self.render();
                    }
                });
            }
        }    
    });
    return AnnotationsView;
});