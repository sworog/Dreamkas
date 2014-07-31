define(function(require) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        urlRoot: function() {
            return Model.baseApiUrl + '/stores/' + 'storeId' + '/invoices/products?validate=true';
        },
        defaults: {
            product: null,
            priceEntered: null,
            quantity: 1
        },
        saveData: function() {

            return {
                product: this.get('product.id'),
                priceEntered: this.get('priceEntered').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi'),
                quantity: this.get('quantity').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi')
            };
        }
    });
});