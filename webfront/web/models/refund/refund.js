define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model'),
        _ = require('lodash');

    return Model.extend({
        defaults: {
            storeId: function(){
                return PAGE.params.storeId;
            }
        },
        urlRoot: function(){
            return Model.baseApiUrl + '/stores/' + this.get('storeId') + '/sales/refund';
        },
        collections: {
            products: require('collections/refundProducts/refundProducts')
        },
        models: {
            sale: function(){
                return PAGE.collections.receipts.get(PAGE.params.receiptId);
            }
        },
        initialize: function(){

            var RefundProductsCollection = require('collections/refundProducts/refundProducts'),
                RefundProductModel = require('models/refundProduct/refundProduct');

            this.collections.products = new RefundProductsCollection(this.models.sale.collections.products.map(function(receiptProductModel){

                var refundProductModel = new RefundProductModel({
                    receiptProduct: receiptProductModel.toJSON()
                });

                refundProductModel.models.receiptProduct = receiptProductModel;

                return refundProductModel;
            }));
        },
        saveData: function(){

            return {
                products: this.collections.products.map(function(refundProductModel) {
                    return refundProductModel.getData();
                }),
                sale: this.models.sale.id,
                date: new Date
            };
        }
    });
});