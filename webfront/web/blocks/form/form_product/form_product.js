define(function(require) {
        //requirements
        var Form = require('kit/form/form'),
            ProductModel = require('models/product/product'),
            normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
            formatNumber = require('kit/formatNumber/formatNumber');

        return Form.extend({
            el: '.form_product',
            model: function(){
                var block = this;

                return new ProductModel();
            },
            events: {
                'keyup [name="purchasePrice"]': function(e){
                    var block = this;

                    block.calculateMarkup();
                },
                'keyup [name="sellingPrice"]': function(e){
                    var block = this;

                    block.calculateMarkup();
                }
            },
            submit: function() {
                var block = this;

                if (block.formData.newGroupName.length){

                    block.formData.subCategory = {
                        name: block.formData.newGroupName
                    };
                }

                return block.model.save(block.formData);
            },
            initialize: function() {

                var block = this;

                Form.prototype.initialize.apply(block, arguments);

                block.calculateMarkup();

                block.on('submit:success', function() {
                    if (!block.__model.id) {
                        block.model = new ProductModel();
                    }
                });
            },
            calculateMarkup: function(){
                var block = this,
                    purchasePrice = normalizeNumber(block.$('[name="purchasePrice"]').val()),
                    sellingPrice = normalizeNumber(block.$('[name="sellingPrice"]').val()),
                    markup = 100 * (sellingPrice - purchasePrice) / purchasePrice,
                    $product__markup = block.$('.product__markup'),
                    $product__markupText = block.$('.product__markupText');

                if (_.isNaN(markup)) {
                    $product__markup.hide();
                } else {
                    $product__markupText.html(formatNumber(markup.toFixed(1)));
                    $product__markup.show();
                }
            }
        });
    }
);