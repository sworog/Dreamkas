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
                    StockMovementsCollection = require('resources/stockMovement/collection'),
                    stockMovementsCollection = new StockMovementsCollection([], {
                        filters: _.pick(page.params, 'dateFrom', 'dateTo', 'types')
                    });

                page.listenTo(stockMovementsCollection, {
                    remove: function() {
                        var $modal = $('.modal:visible');

                        $modal.one('modal.hidden', function(e) {
                            page.render();
                        });

                        if ($modal[0].block) {
                            $modal[0].block.hide();
                        } else {
                            $modal.modal('hide');
                        }
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
                var page = this,
                    invoiceId = e.currentTarget.dataset.invoice_id;

                if (!page.models.invoice || page.models.invoice.id !== invoiceId) {
                    page.models.invoice = page.collections.stockMovements.get(invoiceId);
                    page.render();
                }

                $('#modal_invoiceEdit').modal('show');
            },
            'click .stockIn__link': function(e) {
                var page = this,
                    stockinId = e.currentTarget.dataset.stockinId;

                page.el.querySelector('.modal_stockIn').block.show({
                    models: {
                        stockIn: page.collections.stockMovements.get(stockinId)
                    }
                });
            },
            'click .supplierReturn__link': function(e) {
                var page = this,
                    supplierReturnId = e.currentTarget.dataset.supplierReturnId,
                    SupplierReturnModel = require('resources/supplierReturn/model');

                page.el.querySelector('.modal_supplierReturn').block.show({
                    models: {
                        supplierReturn: page.collections.stockMovements.get(supplierReturnId) || new SupplierReturnModel()
                    }
                });
            },
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
            modal_stockIn: require('blocks/modal/modal_stockIn/modal_stockIn'),
            modal_supplierReturn: require('blocks/modal/modal_supplierReturn/modal_supplierReturn'),
            form_stockMovementsFilters: require('blocks/form/form_stockMovementsFilters/form_stockMovementsFilters'),
            modal_invoiceAdd: function(opt) {
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
            modal_writeOffAdd: function(opt) {
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
            }
        }
    });
});
