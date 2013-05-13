define(
    [
        './baseModel.js'
    ],
    function(baseModel) {
        return baseModel.extend({
            initialize: function(opt){
                this.invoiceId = opt.invoiceId;
            },
            modelName: 'invoiceProduct',
            urlRoot: function(){
                return baseApiUrl + '/invoices/'+ this.invoiceId  + '/products';
            },
            saveFields: [
                'product',
                'quantity',
                'price'
            ]
        });
    });