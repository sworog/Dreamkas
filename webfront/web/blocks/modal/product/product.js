define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        productId: null,
        models: {
            product: null
        },
        render: function() {
            var block = this,
                ProductModel = require('resources/product/model');

            this.models.product = PAGE.collections.groupProducts.get(block.productId) || new ProductModel;

            block.listenTo(this.models.product, {
                destroy: function(){
                    block.hide();
                }
            });

            Modal.prototype.render.apply(this, arguments);
        },
        blocks: {
            form_product: function(){
                var block = this,
                    Form_product = require('blocks/form/product/product');

                return new Form_product({
                    model: block.models.product
                });
            }
        }
    });
});