define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            invoiceModel = require('models/invoice');

        return BaseCollection.extend({
            model: invoiceModel,
            url: baseApiUrl + "/invoices"
        });
    }
);