define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_invoice',
        globalEvents: {
            'submit:success': function(data, block) {

                if (block.el.id === 'form_supplier') {

                    this.model.set('supplier', data);
                    this.render();
                }

                if (block.el.id === 'form_store') {

                    this.model.set('store', data);
                    this.render();
                }

            }
        },
        model: require('resources/invoice/model'),
        collection: function() {
            return PAGE.collections.stockMovements;
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate'),
            select_store: require('blocks/select/store/store'),
            select_supplier: require('blocks/select/supplier/supplier'),
            modal_supplier: require('blocks/modal/supplier/supplier'),
            modal_store: require('blocks/modal/store/store')
        }
    });
});