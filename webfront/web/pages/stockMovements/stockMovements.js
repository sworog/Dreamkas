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
        models: {
            invoice: null,
            writeOff: null
        },
        events: {
            'click .writeOff__link': function(e) {
                var page = this,
                    writeOffId = e.currentTarget.dataset.writeoff_id;

                if (!page.models.writeOff || page.models.writeOff.id !== writeOffId) {
                    page.models.writeOff = page.collections.stockMovements.get(writeOffId);
                    page.render();
                }

                $('#modal_writeOffEdit').modal('show');
            },
            'click .page__addStockInLink': function(e) {
                var page = this,
                    StockInModel = require('resources/stockIn/model');

                page.el.querySelector('.modal_stockIn').block.show({
                    models: {
                        stockIn: new StockInModel
                    }
                });
            }
        },
        blocks: {
            dropdown: require('blocks/dropdown/dropdown'),
			modal_invoice: require('blocks/modal/invoice/invoice'),
            modal_stockIn: require('blocks/modal/stockIn/stockIn'),
            modal_supplierReturn: require('blocks/modal/supplierReturn/supplierReturn'),
			modal_writeOff: require('blocks/modal/writeOff/writeOff'),
            form_stockMovementsFilters: require('blocks/form/form_stockMovementsFilters/form_stockMovementsFilters'),
            table_stockMovements: require('blocks/table/stockMovements/stockMovements')
        }
    });
});
