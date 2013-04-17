define(function() {
    return Backbone.BaseModel.extend({
        modelName: 'invoiceProduct',
        url: function(){
            return baseApiUrl + '/invoices/' + this.get('invoiceId') + '/products.json'
        },
        defaults: {
            id: null,
            product: null,
            invoiceId: null,
            quantity: null,
            price: null
        },
        excludeSaveFields: [
            'invoiceId'
        ]
    });
});