define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        collections: {
            suppliers: function(){
                var SuppliersCollection = require('collections/suppliers/suppliers');

                return new SuppliersCollection();
            },
            stores: function(){
                var StoresCollection = require('collections/stores/stores');

                return new StoresCollection();
            },
            stockMovements: function(){
                var StockMovementsCollection = require('collections/stockMovements/stockMovements');

                return new StockMovementsCollection();
            }
        },
        models: {
            invoice: null
        },
        blocks: {
            modal_invoiceAdd: function(){
                var block = this,
                    Modal_invoice = require('blocks/modal/modal_invoice/modal_invoice');

                return new Modal_invoice({
                    el: '#modal_invoiceAdd',
                    collections: {
                        invoices: block.collections.stockMovements,
                        suppliers: block.collections.suppliers
                    }
                });
            }
        }
    });
});