define(function(require) {
        //requirements
        var Tooltip_form = require('blocks/tooltip/tooltip_form/tooltip_form'),
            Form_catalogGroup = require('blocks/form/form_catalogGroup/form_catalogGroup'),
            CatalogGroupModel = require('models/catalogGroup');

        return Tooltip_form.extend({
            blockName: 'tooltip_catalogGroupForm',
            model: new CatalogGroupModel(),
            collection: null,
            listeners: {
                form: {
                    'submit:success': function() {
                        var block = this;

                        if (!block.form.isAddForm){
                            block.hide();
                        }
                    }
                }
            },
            initialize: function() {
                var block = this;

                Tooltip_form.prototype.initialize.call(this);

                block.form = new Form_catalogGroup({
                    el: block.el.getElementsByClassName('form'),
                    model: block.model,
                    collection: block.collection
                });
            }
        });
    }
);