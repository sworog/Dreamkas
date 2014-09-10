define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Model.extend({
        urlRoot: function(){
            return Model.baseApiUrl + '/stores/' + PAGE.params.storeId + '/sales';
        },
        collections: {
            products: require('collections/receiptProducts/receiptProducts')
        },
        defaults: {
            paymentType: 'cash'
        },
        saveData: function(){

            return {
                products: this.collections.products.map(function(receiptProductModel) {
                    return receiptProductModel.getData();
                }),
                amountTendered: this.get('paymentType') === 'bankcard' ? 999999 : normalizeNumber(this.get('amountTendered')),
                paymentType: this.get('paymentType'),
                date: new Date
            }
        }
    });
});