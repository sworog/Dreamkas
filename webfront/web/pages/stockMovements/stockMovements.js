define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        params: {
            dateFrom: null,
            dateTo: null,
            types: null
        },
        collections: {
            suppliers: require('resources/supplier/collection'),
            stores: require('resources/store/collection'),
            groups: require('resources/group/collection'),
            stockMovements: function() {
                var page = this,
                    StockMovementsCollection = require('resources/stockMovement/collection');

                return new StockMovementsCollection([], {
                    filters: _.pick(page.params, 'dateFrom', 'dateTo', 'types')
                });
            }
        },
        blocks: {
            dropdown: require('blocks/dropdown/dropdown'),
			modal_invoice: require('blocks/modal/stockMovement/invoice/invoice'),
            modal_stockIn: require('blocks/modal/stockMovement/stockIn/stockIn'),
            modal_supplierReturn: require('blocks/modal/stockMovement/supplierReturn/supplierReturn'),
			modal_writeOff: require('blocks/modal/stockMovement/writeOff/writeOff'),
            form_stockMovementsFilters: require('blocks/form/stockMovementsFilters/stockMovementsFilters'),
            table_stockMovements: require('blocks/table/stockMovements/stockMovements')
        }
    });
});
