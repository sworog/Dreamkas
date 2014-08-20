define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection'),
        InvoiceModel = require('models/invoice/invoice'),
        uri = require('uri'),
        WriteOffModel = require('models/writeOff/writeOff');

    return Collection.extend({
        filters: {
            types: null,
            dateFrom: null,
            dateTo: null
        },
        url: function() {
            var collection = this;

            return uri(Collection.baseApiUrl + '/stockMovements').query(collection.filters);
        },
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
                }
            });
        }
    });
});