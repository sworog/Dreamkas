define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/invoiceProduct'),
            initialize: function(opt) {
                this.invoiceId = opt.invoiceId;
            },
            url: function() {
                return LH.baseApiUrl + '/invoices/' + this.invoiceId + '/products'
            }
        });
    }
);