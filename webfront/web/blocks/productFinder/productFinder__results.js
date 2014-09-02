define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        collection: require('collections/products/products'),
        template: require('ejs!./productFinder__results.ejs'),
        query: null
    });
});