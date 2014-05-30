define(function(require) {
    // requirements
    var Model = require('kit/core/model'),
        computeAttr = require('kit/computeAttr/computeAttr'),
        currentUserModel = require('models/currentUser.inst');

    var templates = {
        amount: require('tpl!blocks/amount/amount.html')
    };

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
            inventoryDays: 0,
            averageDailySales: 0,

            inventoryElement: computeAttr(['inventory'], function(inventory) {
                return templates.amount({value: inventory});
            }),

            inventoryDaysElement: computeAttr(['inventoryDays'], function(inventoryDays) {
                return templates.amount({value: inventoryDays});
            }),

            averageDailySalesElement: computeAttr(['averageDailySales'], function(averageDailySales) {
                return templates.amount({value: averageDailySales});
            }),

            unitsFormatted: computeAttr(['product.units'], function() {
                return LH.units(this.get('product.units'), 'smallShort');
            }),

            retailPricePreference: 'retailMarkup',

            averagePurchasePriceFormatted: computeAttr(['averagePurchasePrice'], function(averagePurchasePrice) {
                return averagePurchasePrice ? (LH.formatMoney(averagePurchasePrice) + ' р.') : '&mdash;';
            }),

            averagePurchasePriceElement: computeAttr(['averagePurchasePrice'], function(averagePurchasePrice) {
                if (averagePurchasePrice) {
                    return LH.formatMoney(averagePurchasePrice) +
                        '<span class="layout__currency"> р.</span>'
                } else {
                    return '&mdash;';
                }
            }),

            purchasePriceFormatted: computeAttr(['product.purchasePrice'], function(purchasePrice) {
                return purchasePrice ? (LH.formatMoney(purchasePrice) + ' р.') : '&mdash;';
            }),

            lastPurchasePriceFormatted: computeAttr(['lastPurchasePrice'], function(lastPurchasePrice) {
                var purchasePriceFormatted = this.get('product.purchasePrice') ? (LH.formatMoney(this.get('product.purchasePrice')) + ' р.') : '&mdash;';
                return lastPurchasePrice ? (LH.formatMoney(lastPurchasePrice) + ' р.') : purchasePriceFormatted;
            }),

            lastPurchasePriceElement: computeAttr(['lastPurchasePrice'], function(lastPurchasePrice) {

                lastPurchasePrice = lastPurchasePrice || this.get('product.purchasePrice');

                if (lastPurchasePrice) {
                    return LH.formatMoney(lastPurchasePrice) +
                        '<span class="layout__currency"> р.</span>'
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