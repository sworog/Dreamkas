define(function(require, exports, module) {
    //requirements
    var Form_stockMovement = require('blocks/form/stockMovement/stockMovement');

    return Form_stockMovement.extend({
        collections: {
            suppliers: function(){
                return PAGE.collections.suppliers;
            }
        },
        blocks: {
            select_supplier: function(options) {
                var block = this,
                    SupplierSelect = require('blocks/select/supplier/supplier'),
                    suppliers = block.collections.suppliers,
                    supplier = block.model.get('supplier');

                if (supplier && !suppliers.findWhere({ id: supplier.id })) {
                    suppliers.add(supplier);
                }

                options.collection = suppliers;

                return new SupplierSelect(options);
            }
        }
    });
});