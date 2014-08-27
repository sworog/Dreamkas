define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        params: {
            dateFrom: null,
            dateTo: null,
            types: null
        },
        collections: {
            suppliers: require('collections/suppliers/suppliers'),
            stores: require('collections/stores/stores'),
            groups: require('collections/groups/groups'),
            stockMovements: require('collections/stockMovements/stockMovements')
        },
        blocks: {
            form_stockMovementsFilters: require('blocks/form/form_stockMovementsFilters/form_stockMovementsFilters'),
            modal_stockIn: require('blocks/modal/modal_stockIn/modal_stockIn'),
            modal_supplierReturn: require('blocks/modal/modal_supplierReturn/modal_supplierReturn'),
            modal_invoice: require('blocks/modal/modal_invoice/modal_invoice'),
            modal_writeOff: require('blocks/modal/modal_writeOff/modal_writeOff')
        },
        events: {
            'click .invoice__link': function(e) {
                var page = this,
                    invoiceId = e.currentTarget.dataset.invoiceId,
                    InvoiceModel = require('models/invoice/invoice');

                page.el.querySelector('.modal_invoice').block.show({
                    models: {
                        invoice: page.collections.stockMovements.get(invoiceId) || new InvoiceModel
                    }
                });
            },
            'click .stockIn__link': function(e) {
                var page = this,
                    stockinId = e.currentTarget.dataset.stockinId,
                    StockInModel = require('models/stockIn/stockIn');

                page.el.querySelector('.modal_stockIn').block.show({
                    models: {
                        stockIn: page.collections.stockMovements.get(stockinId) || new StockInModel
                    }
                });
            },
            'click .writeOff__link': function(e) {
                var page = this,
                    writeOffId = e.currentTarget.dataset.writeoffId,
                    WriteOffModel = require('models/writeOff/writeOff');

                page.el.querySelector('.modal_writeOff').block.show({
                    models: {
                        writeOff: page.collections.stockMovements.get(writeOffId) || new WriteOffModel
                    }
                });
            },
            'click .supplierReturn__link': function(e) {
                var page = this,
                    supplierReturnId = e.currentTarget.dataset.supplierReturnId,
                    SupplierReturnModel = require('models/supplierReturn/supplierReturn');

                page.el.querySelector('.modal_supplierReturn').block.show({
                    models: {
                        supplierReturn: page.collections.stockMovements.get(supplierReturnId) || new SupplierReturnModel
                    }
                });
            }
        }
    });
});
