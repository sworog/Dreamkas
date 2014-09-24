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
            payment: {
                type: 'cash',
                amountTendered: null
            },
			amount: function() {
				return this.amount();
			}
        },
        saveData: function(){

            var data = {
                products: this.collections.products.map(function(receiptProductModel) {
                    return receiptProductModel.getData();
                }),
                payment: {
                    type: this.get('payment.type'),
                    amountTendered: normalizeNumber(this.get('payment.amountTendered'))
                },
                date: new Date
            };

            this.get('payment.type') === 'bankcard' && delete data.payment.amountTendered;

            return data;
        }
    });
});