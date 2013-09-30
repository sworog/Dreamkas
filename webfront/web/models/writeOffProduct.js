define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        currentUserModel = require('models/currentUser');

    return Model.extend({
        modelName: 'writeOffProduct',
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/writeoffs/' + this.get('writeOff').id + '/products';
        },
        saveData: [
            'product',
            'quantity',
            'price',
            'cause'
        ]
    });
});