define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection'),
        InvoiceModel = require('models/invoice/invoice'),
        uri = require('uri'),
        WriteOffModel = require('models/writeOff/writeOff'),
        StockInModel = require('models/stockIn/stockIn'),
        SupplierReturnModel = require('models/supplierReturn/supplierReturn');

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
                        collection.add(new InvoiceModel(item));
                        break;

                    case "WriteOff":
                        collection.add(new WriteOffModel(item));
                        break;

                    case "StockIn":
                        collection.add(new StockInModel(item));
                        break;

                    case "SupplierReturn":
                        collection.add(new SupplierReturnModel(item));
                        break;
                }
            });
        }
    });
});