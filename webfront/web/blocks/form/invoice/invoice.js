define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_invoice',
        model: require('resources/invoice/model'),
        collection: function(){
            return PAGE.collections.stockMovements;
        },
        blocks: {
            select_store: require('blocks/select/store/store'),
            select_supplier: require('blocks/select/supplier/supplier')
        }
    });
});