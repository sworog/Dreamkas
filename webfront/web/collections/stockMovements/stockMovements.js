define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection'),
        InvoiceModel = require('models/invoice/invoice');

    return Collection.extend({
        storeId: null,
        url: function(){
            return Collection.baseApiUrl + '/stockMovements';
        },
        parse: function(data) {
            var collection = this;
            data.forEach(function(item) {
                switch (item.type) {
                    case "Invoice":
                        collection.add(new InvoiceModel(item));
                        break;
                }
            });
        }
    });
});