define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection'),
        InvoiceModel = require('models/invoice/invoice'),
        uri = require('uri');

    return Collection.extend({
        filterTypes: '',
        dateFrom: '',
        dateTo: '',
        model: function(attrs){
            var collection = this,
                model;

            switch (attrs.type) {
                case "Invoice":
                    model = new InvoiceModel(attrs);
                    break;
            }

            return model;
        },
        url: function(){
            var collection = this,
                query = _.pick({
                    types: collection.filterTypes,
                    dateFrom: collection.dateFrom,
                    dateTo: collection.dateTo
                }, function(value, key){
                    return value && value.length;
                });

            return uri(Collection.baseApiUrl + '/stockMovements').query(query);
        }
    });
});