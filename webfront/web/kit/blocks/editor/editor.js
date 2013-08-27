define(function(require) {
        //requirements
        var Block = require('kit/block');

        var router = new Backbone.Router();

        return Block.extend({
            __name__: 'editor',
            className: 'editor',
            editMode: false,

            events: {
                'click .editor__on': function(e) {
                    e.preventDefault();
                    var block = this;

                    block.set('editMode', true);
                },
                'click .editor__off': function(e) {
                    e.preventDefault();
                    var block = this;

                    block.set('editMode', false);
                }
            },
            initialize: function() {
                var block = this;

                block.set('editMode', block.editMode);
            },
            'set:editMode': function(editMode) {
                var block = this,
                    url;

                if (editMode) {
                    block.$el.addClass('editor_editMode_on');
                    block.$el.removeClass('editor_editMode_off');
                } else {
                    block.$el.addClass('editor_editMode_off');
                    block.$el.removeClass('editor_editMode_on');
                }
            }
        });
    }
);