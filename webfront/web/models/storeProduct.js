define(function(require) {
    // requirements
    var Model = require('kit/model'),
        currentUserModel = require('models/currentUser');

    return Model.extend({
        modelName: 'storeProduct',
        urlRoot: function() {
            if (currentUserModel.stores && currentUserModel.stores.length) {
                return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/products'
            }
            return LH.baseApiUrl + '/products'
        },
        defaults: {
            retailPricePreference: 'retailMarkup'
        },
        saveFields: [
            'retailPrice',
            'retailMarkup',
            'retailPricePreference',
        ],
        parse: function(response, options) {
            var data = Model.prototype.parse.apply(this, arguments);

            if (typeof data.product == 'object') {
                data.id = data.product.id;
            }

            return data;
        }
    });
});