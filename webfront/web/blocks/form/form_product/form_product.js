define(function(require) {
        //requirements
        var Form = require('kit/form/form');

        return Form.extend({
            el: '.form_product',
            model: function(){
                var block = this,
                    ProductModel = require('models/product/product');
                
                return new ProductModel();
            },
            submit: function() {
                var block = this;

                if (block.formData.newGroupName.length){

                    block.formData.subCategory = {
                        name: block.formData.newGroupName
                    };

                    return block.model.save(block.formData);
                }
            }
        });
    }
);