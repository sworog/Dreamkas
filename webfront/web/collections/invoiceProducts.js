define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            invoiceProductModel = require('models/invoiceProduct');

        return BaseCollection.extend({
            initialize: function(opt) {
                this.invoiceId = opt.invoiceId;
            },
            model: invoiceProductModel,
            url: function() {
                return LH.baseApiUrl + '/invoices/' + this.invoiceId + '/products'
            }
        });
    }
);