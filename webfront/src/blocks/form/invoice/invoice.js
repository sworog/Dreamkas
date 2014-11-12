define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_invoice',
        modalId: null,
        globalEvents: {
            'submit:success': function(data, block) {

                var modal = block.$el.closest('.modal')[0];

                if (modal && modal.id === 'modal_supplierForInvoice' + this.cid) {

                    this.model.set('supplier', data);
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
            modal_supplier: require('blocks/modal/supplier/supplier')
        }
    });
});