define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock'),
        _ = require('lodash');

    return CollectionBlock.extend({
        collection: require('collections/products/products'),
        template: require('ejs!./productFinder__results.ejs'),
		itemSelector: '.productFinder__resultLink',
        highlight: function(string){

            var block = this;

            return _.escape(string).replace(new RegExp(block.collection.filters.query, 'gi'), '<b>' + block.collection.filters.query + '</b>');
        }
    });
});