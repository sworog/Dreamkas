define(function(require) {
    // requirements
    var Model = require('kit/core/model'),
        computeAttr = require('kit/utils/computeAttr'),
        currentUserModel = require('models/currentUser');

    return Model.extend({
        modelName: 'storeProduct',
        initData: {
            product: require('models/product'),
            store: require('models/store')
        },
        urlRoot: function() {
            if (currentUserModel.stores.length) {
                return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/products'
            }
            return LH.baseApiUrl + '/products'
        },
        defaults: {
            amount: 0,
            unitsFormatted: computeAttr(['product.units'], function(){
                return LH.units(this.get('product.units'), 'smallShort');
            }),
            retailPricePreference: 'retailMarkup',
            averagePurchasePriceFormatted: computeAttr(['averagePurchasePrice'], function(averagePurchasePrice){
                return averagePurchasePrice ? (LH.formatPrice(averagePurchasePrice) + ' р.') : '&mdash;'
            }),
            purchasePriceFormatted: computeAttr(['product.purchasePrice'], function(purchasePrice){
                return purchasePrice ? (LH.formatPrice(purchasePrice) + ' р.') : '&mdash;';
            }),
            lastPurchasePriceFormatted: computeAttr(['lastPurchasePrice'], function(lastPurchasePrice){
                var purchasePriceFormatted = this.get('product.purchasePrice') ? (LH.formatPrice(this.get('product.purchasePrice')) + ' р.') : '&mdash;';
                return lastPurchasePrice ? (LH.formatPrice(lastPurchasePrice) + ' р.') : purchasePriceFormatted;
            })
        },
        saveData: [
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