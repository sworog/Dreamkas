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
        initialize: function(data){

            data = data || {};

            if (typeof data.deleted === 'undefined'){
                this.deleted = false;
            }

            return Modal.prototype.initialize.apply(this, arguments);
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