define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
		productId: 0,
		id: 'modal_product',
		template: require('ejs!./template.ejs'),
		models: {
            product: null
        },
        render: function(){

            var block = this,
                ProductModel = require('resources/product/model'),
                groupProducts = PAGE.get('collections.groupProducts'),
                productModel;

            productModel = groupProducts && groupProducts.get(this.productId);

            block.models.product = productModel || new ProductModel;

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