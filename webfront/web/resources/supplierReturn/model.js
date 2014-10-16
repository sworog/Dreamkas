define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        cookies = require('cookies');

    return Model.extend({
        storeId: null,
        collections: {
            products: require('resources/supplierReturnProduct/collection')
        },
        urlRoot: Model.baseApiUrl + '/supplierReturns',
        defaults: {
            paid: false
        },
        saveData: function() {

            return {
                supplier: this.get('supplier'),
                date: this.get('date'),
                products: this.collections.products.map(function(productModel) {
                    return productModel.getData();
                }),
                paid: this.get('paid'),
                store: this.get('store')
            }
        },
        validateProducts: function() {
            var model = this;

            return model.save(null, {
                url: this.url() + '?validate=1&validationGroups=products'
            });
        }
    });
});