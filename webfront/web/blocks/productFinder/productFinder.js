define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        collections: {
            products: require('collections/products/products')
        },
        models: {
            receipt: require('models/receipt/receipt')
        },
        events: {
            'keyup input[name="product"]': function(e){
                var block = this;

                e.target.value.length >= 3 && e.target.classList.add('loading');

                block.collections.products.find(e.target.value).then(function(){
                    e.target.classList.remove('loading');
                });
            },
            'click .productFinder__resetLink': function(e){
                e.preventDefault();

                var block = this;

                block.$('input[name="product"]').val('');

                block.collections.products.searchQuery = null;
                block.collections.products.reset([]);
            },
            'click .productFinder__resultLink': function(e){
                e.preventDefault();

                var block = this;

                block.models.receipt.collections.products.add({
                    product: block.collections.products.get(e.currentTarget.dataset.productId).toJSON()
                });
            }
        },
        blocks: {
            productFinder__results: function(opt){
                var block = this,
                    ProductFinder__results = require('./productFinder__results');

                return new ProductFinder__results({
                    el: opt.el,
                    collection: block.collections.products
                });
            }
        }
    });
});