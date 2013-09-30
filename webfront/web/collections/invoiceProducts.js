define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/invoiceProduct'),
            initialize: function(opt) {
                this.invoiceId = opt.invoiceId;
                this.storeId = opt.storeId;
            },
            url: function() {
                return LH.baseApiUrl + '/stores/' + this.storeId + '/invoices/' + this.invoiceId + '/products'
            }
        });
    }
);