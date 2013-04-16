define(function() {
    return Backbone.BaseModel.extend({
        defaults: {
            id: null,
            product: null,
            productModel: null,
            invoice: null,
            invoiceModel: null,
            quantity: null,
            price: null
        },

        excludeSaveFields: [
            'invoice',
            'invoiceModel',
            'productModel'
        ]
    });
});