define(function(require) {
        //requirements
        var Tooltip = require('kit/blocks/tooltip/tooltip'),
            Form_catalogClass = require('blocks/form/form_catalogClass/form_catalogClass'),
            CatalogClassModel = require('models/catalogClass');

        return Tooltip.extend({
            catalogClassModel: new CatalogClassModel(),
            catalogClassesCollection: null,
            isAddForm: true,
            addClass: 'tooltip_catalogClassForm',
            templates: {
                content: require('tpl!./templates/index.html')
            },
            listeners: {
                form: {
                    'submit:success': function() {
                        var block = this;
                        if (block.isAddForm){
                            block.hide();
                        }
                    }
                }
            },
            initialize: function() {
                var block = this;

                Tooltip.prototype.initialize.call(this);

                block.form = new Form_catalogClass({
                    el: block.el.getElementsByClassName('form'),
                    model: block.catalogClassModel,
                    collection: block.catalogClassesCollection
                });

                if (block.catalogClassModel.id){
                    block.isAddForm = false;
                }
            },
            show: function(opt) {
                var block = this;

                Tooltip.prototype.show.apply(this, arguments);

                block.initialize();

                block.form.$el.find('[type="text"]').eq(0).focus();
            }
        });
    }
);