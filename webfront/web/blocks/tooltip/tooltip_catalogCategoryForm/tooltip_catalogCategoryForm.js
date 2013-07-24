define(function(require) {
        //requirements
        var Tooltip_form = require('blocks/tooltip/tooltip_form/tooltip_form'),
            CatalogCategoryModel = require('models/catalogCategory'),
            Form_catalogCategory = require('blocks/form/form_catalogCategory/form_catalogCategory');

        return Tooltip_form.extend({
            __name__: 'tooltip_catalogCategoryForm',
            model: new CatalogCategoryModel(),
            collection: null,
            listeners: {
                form: {
                    'submit:success': function() {
                        var block = this;
                        if (!block.form.isAddForm) {
                            block.hide();
                        }
                    }
                }
            },
            initialize: function() {
                var block = this;

                Tooltip_form.prototype.initialize.call(this);

                block.form = new Form_catalogCategory({
                    el: block.el.getElementsByClassName('form'),
                    model: block.model,
                    collection: block.collection
                });
            }
        });
    }
);