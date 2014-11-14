define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_invoice',
        model: require('resources/invoice/model'),
        collection: function() {
            return PAGE.collections.stockMovements;
        },
        initialize: function() {
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.listenTo(block.model.collections.products, {
                'add': function() {
                    block.removeErrors();
                }
            });
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate'),
            select_store: require('blocks/select/store/store'),
            select_supplier: require('blocks/select/supplier/supplier')
        }
    });
});