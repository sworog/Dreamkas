define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        currentUserModel = require('models/currentUser');

    return Model.extend({
        modelName: 'invoiceProduct',
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/invoices/' + this.get('invoice').id + '/products';
        },
        saveData: [
            'product',
            'quantity',
            'price'
        ]
    });
});