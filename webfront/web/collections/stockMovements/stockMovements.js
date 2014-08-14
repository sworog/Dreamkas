define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection'),
        InvoiceModel = require('models/invoice/invoice'),
        uri = require('uri'),
        WriteOffModel = require('models/writeOff/writeOff');

    return Collection.extend({
        filterTypes: '',
        dateFrom: '',
        dateTo: '',
        url: function() {
            var collection = this,
                query = _.pick({
                    types: collection.filterTypes,
                    dateFrom: collection.dateFrom,
                    dateTo: collection.dateTo
                }, function (value, key) {
                    return value && value.length;
                });

            return uri(Collection.baseApiUrl + '/stockMovements').query(query);
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