define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        query: null,
        request: null,
        template: require('ejs!./template.ejs'),
        collections: {
            products: require('collections/products/products')
        },
        events: {
            'keyup input[name="product"]': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.collections.products.find(e.target.value).then(function(){
                    e.target.classList.remove('loading');
                });
            },
            'click .productFinder__resetLink': function(e){
                e.preventDefault();

                var block = this;

                block.$('input[name="product"]').val('');

                block.collections.products.reset([]);
            }
        }
    });
});