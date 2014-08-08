define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        params: {
            filterTypes: ''
        },
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

                return new StockMovementsCollection({
                    filterTypes: this.params.filterTypes
                });
            },
            groups: function(){
                var GroupsCollection = require('collections/groups/groups');

                return new GroupsCollection();
            }
        },
        models: {
            invoice: null
        },
        events: {
            'change select[name=filterTypes]': function(e) {
                var page = this;

                e.currentTarget.classList.add('loading');

                page.params.filterTypes = e.target.value;

                router.save(page.params);

                page.collections.stockMovements.filterTypes = page.params.filterTypes;
                page.collections.stockMovements.fetch().then(function() {
                    page.render();
                });
            }
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