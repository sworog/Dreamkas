define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection'),
        _ = require('lodash'),
        get = require('kit/get/get'),
        uri = require('uri');

    return Collection.extend({
        filters: function() {
            return _.pick(PAGE.params, 'dateFrom', 'dateTo', 'types');
        },
        url: function() {
            var collection = this;

            return uri(Collection.baseApiUrl + '/stockMovements').query(get(collection, 'filters')).toString();
        },
        model: function(attrs, opt) {
            var model;

            switch (attrs.type) {
                case 'Invoice':
                    model = require('models/invoice/invoice');
                    break;

                case 'WriteOff':
                    model = require('models/writeOff/writeOff');
                    break;

                case 'StockIn':
                    model = require('models/stockIn/stockIn');
                    break;

                case "SupplierReturn":
                    model = require('models/supplierReturn/supplierReturn');
                    break;
            }

            return model.apply(null, arguments);
        }
    });
});