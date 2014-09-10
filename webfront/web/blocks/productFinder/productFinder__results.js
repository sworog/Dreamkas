define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock'),
        _ = require('lodash');

    return CollectionBlock.extend({
        collection: require('collections/products/products'),
        template: require('ejs!./productFinder__results.ejs'),
        highlight: function(string){

            var block = this;

            return _.escape(string).replace(new RegExp(block.collection.searchQuery, 'gi'), '<b>' + block.collection.searchQuery + '</b>');
        }
    });
});