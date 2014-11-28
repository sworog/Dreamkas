define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        deleted: false,
		productId: 0,
		id: 'modal_product',
        selectedGroupId: function(){
            return PAGE.models.group && PAGE.models.group.id;
        },
		models: {
            product: null,
            group: function(){
                return PAGE.collections.groups.get(this.get('selectedGroupId'));
            }
        },
        render: function(){

            var block = this,
                ProductModel = require('resources/product/model'),
                groupProducts = PAGE.get('collections.groupProducts'),
                productModel;

            productModel = groupProducts && groupProducts.get(this.productId);

            block.models.product = productModel || new ProductModel;

            return Modal.prototype.render.apply(this, arguments);
        },
        blocks: {
            form_product: function(opt){
                var block = this,
                    Form_product = require('blocks/form/product/product');

                return new Form_product(_.extend({
                    model: block.models.product
                }, opt));
            }
        }
    });
});