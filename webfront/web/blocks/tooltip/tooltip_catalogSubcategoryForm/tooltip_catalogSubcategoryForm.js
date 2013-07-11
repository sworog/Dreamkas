define(function(require) {
        //requirements
        var Tooltip_form = require('blocks/tooltip/tooltip_form/tooltip_form'),
            CatalogSubcategoryModel = require('models/catalogSubcategory'),
            Form_catalogSubcategory = require('blocks/form/form_catalogSubcategory/form_catalogSubcategory');

        return Tooltip_form.extend({
            blockName: 'tooltip_catalogSubcategoryForm',
            model: new CatalogSubcategoryModel(),
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

                block.form = new Form_catalogSubcategory({
                    el: block.el.getElementsByClassName('form'),
                    model: block.model,
                    collection: block.collection
                });
            }
        });
    }
);