define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        moment = require('moment'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        params: {
            dateTo: function() {
                var page = this,
                    currentTime = Date.now();

                return page.formatDate(moment(currentTime));
            },
            dateFrom: function() {
                var page = this,
                    currentTime = Date.now();

                return page.formatDate(moment(currentTime).subtract(1, 'week'));
            },
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
        },
        initialize: function(){

            this.params.dateTo = this.get('params.dateTo');
            this.params.dateFrom = this.get('params.dateFrom');

            return Page.prototype.initialize.apply(this, arguments);
        }
    });
});
