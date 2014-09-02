define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        defaults: {
            product: {},
            count: 1
        },
        saveData: function() {

            return {
                product: this.get('product.id'),
                count: this.get('count')
            };
        }
    });
});