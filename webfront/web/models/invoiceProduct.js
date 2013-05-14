define(
    [
        './baseModel.js'
    ],
    function(baseModel) {
        return baseModel.extend({
            modelName: 'invoiceProduct',
            urlRoot: function(){
                return baseApiUrl + '/invoices/'+ this.get('invoice').id  + '/products';
            },
            saveFields: [
                'product',
                'quantity',
                'price'
            ]
        });
    });