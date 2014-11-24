define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        collection: function() {
            return PAGE.collections.stockMovements;
        },
        collections: {
            stores: function() {
                return PAGE.collections.stores;
            },
            suppliers: function(){
                return PAGE.collections.suppliers;
            }
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate'),
            select_store: require('blocks/select/store/store'),
            select_supplier: function(options) {
                var block = this,
                    SupplierSelect = require('blocks/select/supplier/supplier'),
                    suppliers = block.collections.suppliers.clone(),
                    supplier = block.model.get('supplier');

                if (supplier && !suppliers.findWhere({ id: supplier.id })) {
                    suppliers.add(supplier);
                }

                options.collection = suppliers;

                return new SupplierSelect(options);
            }
        },
        initialize: function() {
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.listenTo(block.model.collections.products, {
                'add': function() {
                    block.removeErrors();
                }
            });
        }
    });
});