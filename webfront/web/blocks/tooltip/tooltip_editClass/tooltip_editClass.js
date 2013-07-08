define(function(require) {
        //requirements
        var Tooltip = require('kit/blocks/tooltip/tooltip'),
            Form = require('blocks/form/form'),
            CatalogClassModel = require('models/catalogClass');

        return Tooltip.extend({
            classModel: null,
            addClass: 'tooltip_editClass',
            templates: {
                content: require('tpl!blocks/tooltip/tooltip_editClass/templates/index.html')
            },

            initialize: function() {
                var block = this;

                block.classModel = block.classModel || new CatalogClassModel();

                Tooltip.prototype.initialize.call(this);

                block.form = new Form({
                    el: block.el.getElementsByClassName('form'),
                    model: block.classModel
                });

                block.listenTo(block.form, {
                    successSubmit: function() {
                        block.hide();
                    }
                });
            },
            show: function() {
                var block = this;

                Tooltip.prototype.show.apply(this, arguments);

                block.form.$el.find('[type="text"]').eq(0).focus();
            }
        });
    }
);