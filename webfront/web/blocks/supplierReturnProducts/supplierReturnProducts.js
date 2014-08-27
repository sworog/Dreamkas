define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        template: require('ejs!./supplierReturnProducts.ejs')
    });
});