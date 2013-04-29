define(
    [
        './baseModel.js'
    ],
    function(baseModel) {
        return baseModel.extend({
            modelName: 'invoiceProduct',

            saveFields: [
                'product',
                'quantity',
                'price'
            ]
        });
    });