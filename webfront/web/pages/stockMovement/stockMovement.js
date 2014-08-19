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
            suppliers: require('collections/suppliers/suppliers'),
            stores: require('collections/stores/stores'),
            stockMovements: function(){
                var page = this,
                    StockMovementsCollection = require('collections/stockMovements/stockMovements'),
                    stockMovementsCollection = new StockMovementsCollection([], {
                        filterTypes: page.params.filterTypes,
                        dateFrom: page.params.dateFrom,
                        dateTo: page.params.dateTo
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
            },
            groups: require('collections/groups/groups')
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
            },
            modal_invoiceEdit: function() {
                var block = this,
                    Modal_invoice = require('blocks/modal/modal_invoice/modal_invoice');

                if (block.models.invoice) {
                    return new Modal_invoice({
                        el: '#modal_invoiceEdit',
                        collections: {
                            invoices: block.collections.stockMovements,
                            suppliers: block.collections.suppliers
                        },
                        models: {
                            invoice: block.models.invoice
                        }
                    });
                }
            },
            modal_writeOffAdd: function(){
                var block = this,
                    Modal_writeOff = require('blocks/modal/modal_writeOff/modal_writeOff');

                return new Modal_writeOff({
                    el: '#modal_writeOffAdd',
                    collections: {
                        stockMovements: block.collections.stockMovements
                    }
                });
            },
            modal_writeOffEdit: function() {
                var block = this,
                    Modal_writeOff = require('blocks/modal/modal_writeOff/modal_writeOff');

                if (block.models.writeOff) {
                    return new Modal_writeOff({
                        el: '#modal_writeOffEdit',
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
