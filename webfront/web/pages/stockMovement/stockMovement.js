define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        params: {
            filters: {}
        },
        collections: {
            suppliers: require('collections/suppliers/suppliers'),
            stores: require('collections/stores/stores'),
            groups: require('collections/groups/groups'),
            stockMovements: function(){
                var page = this,
                    StockMovementsCollection = require('collections/stockMovements/stockMovements'),
                    stockMovementsCollection = new StockMovementsCollection([], {
                        filters: page.params.filters
                    });

                page.listenTo(stockMovementsCollection, {
                    remove: function(){
                        var modal = $('.modal:visible');

                        modal.one('hidden.bs.modal', function(e) {
                            page.render();
                        });

                        modal.modal('hide');
                    }
                });

                return stockMovementsCollection;
            }
        },
        models: {
            invoice: null,
            writeOff: null
        },
        events: {
            'click .invoice__link': function(e) {
                var block = this,
                    invoiceId = e.currentTarget.dataset.invoice_id;

                if (!block.models.invoice || block.models.invoice.id !== invoiceId) {
                    block.models.invoice = block.collections.stockMovements.get(invoiceId);
                    block.render();
                }

                $('#modal_invoiceEdit').modal('show');
            },
            'click .writeOff__link': function(e) {
                var block = this,
                    writeOffId = e.currentTarget.dataset.writeoff_id;

                if (!block.models.writeOff || block.models.writeOff.id !== writeOffId) {
                    block.models.writeOff = block.collections.stockMovements.get(writeOffId);
                    block.render();
                }

                $('#modal_writeOffEdit').modal('show');
            }
        },
        blocks: {
            modal_invoiceAdd: function(opt){
                var page = this,
                    Modal_invoice = require('blocks/modal/modal_invoice/modal_invoice');

                return new Modal_invoice({
                    el: opt.el,
                    collections: {
                        invoices: page.collections.stockMovements,
                        suppliers: page.collections.suppliers
                    }
                });
            },
            modal_invoiceEdit: function(opt) {
                var page = this,
                    Modal_invoice = require('blocks/modal/modal_invoice/modal_invoice');

                if (page.models.invoice) {
                    return new Modal_invoice({
                        el: opt.el,
                        collections: {
                            invoices: page.collections.stockMovements,
                            suppliers: page.collections.suppliers
                        },
                        models: {
                            invoice: page.models.invoice
                        }
                    });
                }
            },
            modal_writeOffAdd: function(opt){
                var block = this,
                    Modal_writeOff = require('blocks/modal/modal_writeOff/modal_writeOff');

                return new Modal_writeOff({
                    el: opt.el,
                    collections: {
                        stockMovements: block.collections.stockMovements
                    }
                });
            },
            modal_writeOffEdit: function(opt) {
                var block = this,
                    Modal_writeOff = require('blocks/modal/modal_writeOff/modal_writeOff');

                if (block.models.writeOff) {
                    return new Modal_writeOff({
                        el: opt.el,
                        collections: {
                            writeOffs: block.collections.stockMovements
                        },
                        models: {
                            writeOff: block.models.writeOff
                        }
                    });
                }
            },
            form_stockMovementsFilters: require('blocks/form/form_stockMovementsFilters/form_stockMovementsFilters')
        }
    });
});
