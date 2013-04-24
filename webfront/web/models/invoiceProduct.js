define(
    [
        './main.js'
    ],
    function(BaseModel) {
    return BaseModel.extend({
        modelName: 'invoiceProduct',
        url: function(){
            var url;
            if (this.id){
                url = baseApiUrl + '/invoices/' + this.get('invoiceId') + '/products/' + this.id + '.json';
            } else {
                url = baseApiUrl + '/invoices/' + this.get('invoiceId') + '/products.json';
            }
            return url;
        },
        defaults: {
            id: null,
            product: null,
            invoiceId: null,
            quantity: null,
            price: null
        },
        parse: function(data){
            data.invoiceId = data.invoice.id;
            return data;
        },
        excludeSaveFields: [
            'invoiceId',
            'totalPrice',
            'invoice',
            'productPrice'
        ]
    });
});