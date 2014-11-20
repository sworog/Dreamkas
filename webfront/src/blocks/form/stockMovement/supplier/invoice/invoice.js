define(function(require, exports, module) {
    //requirements
    var Form_supplierStockMovement = require('blocks/form/stockMovement/supplier/supplier');

    return Form_supplierStockMovement.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_invoice',
        model: require('resources/invoice/model'),
        initialize: function() {
            var block = this;

            Form_supplierStockMovement.prototype.initialize.apply(block, arguments);

            block.listenTo(block.model.collections.products, {
                'add': function() {
                    block.removeErrors();
                }
            });
        }
    });
});