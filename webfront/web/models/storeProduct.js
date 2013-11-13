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
            inventory: 0,

            inventoryElement: computeAttr(['inventory'], function(inventoryDays) {
                return String.prototype.split.call(inventoryDays, '.')[0] + '<span class="layout__floatPart">,' + (String.prototype.split.call(inventoryDays, '.')[1] || '000') + '</span>'
            }),

            inventoryDaysElement: computeAttr(['inventoryDays'], function(inventoryDays) {
                if (inventoryDays) {
                    return String.prototype.split.call(inventoryDays, '.')[0] + '<span class="layout__floatPart">,' + (String.prototype.split.call(inventoryDays, '.')[1] || '0') + '</span>'
                } else {
                    return '&mdash;';
                }
            }),

            averageDailySalesElement: computeAttr(['averageDailySales'], function(averageDailySales) {
                if (averageDailySales) {
                    return String.prototype.split.call(averageDailySales, '.')[0] + '<span class="layout__floatPart">,' + (String.prototype.split.call(averageDailySales, '.')[1] || '000') + '</span>'
                } else {
                    return '&mdash;';
                }
            }),

            unitsFormatted: computeAttr(['product.units'], function() {
                return LH.units(this.get('product.units'), 'smallShort');
            }),

            retailPricePreference: 'retailMarkup',

            averagePurchasePriceFormatted: computeAttr(['averagePurchasePrice'], function(averagePurchasePrice) {
                return averagePurchasePrice ? (LH.formatPrice(averagePurchasePrice) + ' р.') : '&mdash;';
            }),

            averagePurchasePriceElement: computeAttr(['averagePurchasePrice'], function(averagePurchasePrice) {
                if (averagePurchasePrice) {
                    return String.prototype.split.call(averagePurchasePrice, '.')[0] + '<span class="layout__floatPart">,' + String.prototype.split.call(averagePurchasePrice, '.')[1] + '</span>'
                } else {
                    return '&mdash;';
                }
            }),

            purchasePriceFormatted: computeAttr(['product.purchasePrice'], function(purchasePrice) {
                return purchasePrice ? (LH.formatPrice(purchasePrice) + ' р.') : '&mdash;';
            }),

            lastPurchasePriceFormatted: computeAttr(['lastPurchasePrice'], function(lastPurchasePrice) {
                var purchasePriceFormatted = this.get('product.purchasePrice') ? (LH.formatPrice(this.get('product.purchasePrice')) + ' р.') : '&mdash;';
                return lastPurchasePrice ? (LH.formatPrice(lastPurchasePrice) + ' р.') : purchasePriceFormatted;
            }),

            lastPurchasePriceElement: computeAttr(['lastPurchasePrice'], function(lastPurchasePrice) {
                lastPurchasePrice = lastPurchasePrice || this.get('product.purchasePrice');
                if (lastPurchasePrice) {
                    return String.prototype.split.call(lastPurchasePrice, '.')[0] + '<span class="layout__floatPart">,' + String.prototype.split.call(lastPurchasePrice, '.')[1] + '</span>'
                } else {
                    return '&mdash;';
                }
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