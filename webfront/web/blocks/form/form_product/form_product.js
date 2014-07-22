define(function(require) {
        //requirements
        var Form = require('kit/form/form');

        return Form.extend({
            el: '.form_product',
            model: function(){
                var block = this,
                    ProductModel = require('models/product/product');
                
                return new ProductModel();
            }
        });
    }
);