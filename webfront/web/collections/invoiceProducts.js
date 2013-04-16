define(
    [
        '/models/invoiceProduct.js'
    ],
    function(invoiceProduct) {
        return Backbone.BaseCollection.extend({
            initialize: function(opt){
                this.invoiceId = opt.invoiceId;
            },
            model: invoiceProduct,
            url: function() {
                return baseApiUrl + "/invoices/"+ this.invoiceId  +"/products.json"
            }
        });
    }
);