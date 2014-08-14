define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        cookies = require('cookies');

    return Model.extend({
        storeId: null,
        fromOrder: null,
        collections: {
            products: function() {
                var model = this,
                    WriteOffProductsCollection = require('collections/writeOffProducts/writeOffProducts');

                return new WriteOffProductsCollection();
            }
        },
        urlRoot: function() {
            return Model.baseApiUrl + '/writeOffs'
        },
        defaults: {
            paid: false
        },
        saveData: function() {
            return {
                date: this.get('date'),
                products: this.collections.products.map(function(productModel) {
                    return productModel.getData();
                }),
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