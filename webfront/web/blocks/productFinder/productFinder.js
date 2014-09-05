define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        checkKey = require('kit/checkKey/checkKey');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        collections: {
            products: require('collections/products/products')
        },
        models: {
            receipt: function(){
                return PAGE.models.receipt;
            }
        },
        events: {
            'keyup input[name="product"]': function(e) {
                var block = this;

                if (checkKey(e.keyCode, ['UP', 'DOWN', 'LEFT', 'RIGHT', 'ESC'])) {
                    return;
                }

                e.target.value.length >= 3 && e.target.classList.add('loading');

                block.collections.products.find(e.target.value).then(function() {
                    e.target.classList.remove('loading');
                });
            },
            'click .productFinder__resetLink': function(e) {
                e.preventDefault();

                var block = this;

                block.reset();
            },
            'click .productFinder__resultLink': function(e) {
                e.preventDefault();

                var block = this;

                block.addProductToReceipt(e.currentTarget.dataset.productId);
            }
        },
        blocks: {
            productFinder__results: function(opt) {
                var block = this,
                    ProductFinder__results = require('./productFinder__results');

                return new ProductFinder__results({
                    el: opt.el,
                    collection: block.collections.products
                });
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            $(document).on('keyup', function(e) {
                if (checkKey(e.keyCode, ['ESC'])) {
                    block.reset();
                }

                if (checkKey(e.keyCode, ['UP'])) {
                    block.focusProduct(block.get('focusedProductIndex') - 1);
                }

                if (checkKey(e.keyCode, ['DOWN'])) {
                    block.focusProduct(block.get('focusedProductIndex') + 1);
                }
            });
        },

        focusedProductIndex: function(){
            var block = this;

            return block.$('.productFinder__resultLink').index(document.activeElement);
        },

        addProductToReceipt: function(productId) {
            var block = this,
                ReceiptProductModel = require('models/receiptProduct/receiptProduct'),
                receiptProductModel = new ReceiptProductModel({
                    product: block.collections.products.get(productId).toJSON()
                });

            if (receiptProductModel.get('sellingPrice')){
                block.models.receipt.collections.products.add(receiptProductModel);
            } else {
                document.getElementById('modal_receiptProduct').block.show({
                    models: {
                        receiptProduct: receiptProductModel
                    }
                });
            }
        },
        reset: function(){
            var block = this;

            block.$('input[name="product"]').val('').focus();

            block.collections.products.searchQuery = null;
            block.collections.products.reset([]);
        },
        focusProduct: function(productIndex) {
            var block = this;

            var links = block.el.querySelectorAll('.productFinder__resultLink');

            if (!links.length) {
                return;
            }

            if (links[productIndex]) {
                links[productIndex].focus();
            } else if (productIndex < 0) {
                block.focusProduct(0);
            } else {
                block.focusProduct(links.length - 1);
            }
        }
    });
});