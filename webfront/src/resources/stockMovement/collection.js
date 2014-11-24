define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection'),
        InvoiceModel = require('resources/invoice/model'),
        WriteOffModel = require('resources/writeOff/model'),
        StockInModel = require('resources/stockIn/model'),
        SupplierReturnModel = require('resources/supplierReturn/model');

    return Collection.extend({
        filters: {
            types: null,
            dateFrom: null,
            dateTo: null
        },
        url: Collection.baseApiUrl + '/stockMovements',
        parse: function(data) {
            var collection = this;
            data.forEach(function(item) {
                switch (item.type) {
                    case "Invoice":
                        collection.add(new InvoiceModel(item), {
                            silent: true
                        });
                        break;

                    case "WriteOff":
                        collection.add(new WriteOffModel(item), {
                            silent: true
                        });
                        break;

                    case "StockIn":
                        collection.add(new StockInModel(item), {
                            silent: true
                        });
                        break;

                    case "SupplierReturn":
                        collection.add(new SupplierReturnModel(item), {
                            silent: true
                        });
                        break;
                }
            });
        }
    });
});