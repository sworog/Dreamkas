define(function(require) {
        //requirements
        var Tooltip_form = require('blocks/tooltip/tooltip_form/tooltip_form'),
            CatalogSubCategoryModel = require('models/catalogSubCategory'),
            Form_catalogSubCategory = require('blocks/form/form_catalogSubCategory/form_catalogSubCategory');

        return Tooltip_form.extend({
            __name__: 'tooltip_catalogSubCategoryForm',
            model: new CatalogSubCategoryModel(),
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

                block.form = new Form_catalogSubCategory({
                    el: block.el.getElementsByClassName('form'),
                    model: block.model,
                    collection: block.collection
                });
            }
        });
    }
);