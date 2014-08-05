define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection/collection');

    return Collection.extend({
        model: require('models/barcode'),
        productId: null,
        validateBarcode: function(barcode){

        }
    });
});