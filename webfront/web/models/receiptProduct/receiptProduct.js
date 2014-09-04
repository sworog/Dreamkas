define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        defaults: {
            count: 1,
            sellingPrice: null
        },
        models: {
            product: require('models/product/product')
        },
        initialize: function(){
            this.get('sellingPrice') || this.set('sellingPrice', this.models.product.get('sellingPrice') || 0)
        },
        saveData: function() {

            return {
                product: this.models.product.id,
                count: this.get('count'),
                sellingPrice: this.get('sellingPrice')
            };
        },
        validate: function(){
            var deferred = $.Deferred();

            deferred.resolve();

            return deferred.promise();
        }
    });
});