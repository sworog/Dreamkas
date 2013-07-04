define(function(require) {

        //requirements
        var Editor = require('kit/blocks/editor/editor'),
            params = require('pages/catalog/params');

        return Editor.extend({
            blockName: 'catalog',
            templates: {
                index: require('tpl!./templates/index.html')
            },
            events: {
                'click .catalog__addClassLink': function(e) {
                    e.preventDefault();

                    var block = this,
                        $el = $(e.target);

                }
            },
            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);


            },
            renderClassList: function() {
                var block = this;

                block.$classList
                    .html(block.templates.classList({
                        block: block
                    }));
            },
            'set:editMode': function(editMode){
                Editor.prototype['set:editMode'].apply(this, arguments);
                params.editMode = editMode;
            }
        })
    }
);