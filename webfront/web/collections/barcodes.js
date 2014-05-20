define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('models/barcode'),
        productId: null,
        validateBarcode: function(barcode){

        }
    });
});