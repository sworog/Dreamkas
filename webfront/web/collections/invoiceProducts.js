define(
    [
        './baseCollection.js',
        '/models/invoiceProduct.js'
    ],
    function(BaseCollection, invoiceProduct) {
        return BaseCollection.extend({
            initialize: function(opt){
                this.invoiceId = opt.invoiceId;
            },
            model: invoiceProduct,
            url: function() {
                return baseApiUrl + '/invoices/'+ this.invoiceId  + '/products.json'
            }
        });
    }
);