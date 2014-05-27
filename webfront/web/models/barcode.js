define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        defaults: {
            barcode: null,
            quantity: null,
            price: null
        }
    });
});