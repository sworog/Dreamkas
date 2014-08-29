define(function(require) {
        //requirements
        var Form = require('kit/form/form.deprecated'),
            ProductModel = require('models/product/product'),
            normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
            formatNumber = require('kit/formatNumber/formatNumber');

        return Form.extend({
            el: '.form_product',
            model: function(){
                var block = this;

                return new ProductModel();
            },
            blocks: {
                select_group: function() {
                    var block = this,
                        Select_group = require('blocks/select/select_group/select_group');

                    return new Select_group({
                        el: block.$('.select_group')
                    });
                }
            },
            events: {
                'keyup [name="purchasePrice"]': function(e){
                    var block = this;

                    block.calculateMarkup();
                },
                'keyup [name="sellingPrice"]': function(e){
                    var block = this;

                    block.calculateMarkup();
                },
                'click .confirmLink__confirmation .product__removeLink': function(e) {
                    var block = this;

                    e.target.classList.add('loading');

                    block.model.destroy().then(function() {
                        e.target.classList.remove('loading');
                    });
                }
            },
            showFieldError: function(data, field) {
                var block = this;

                if (field === 'subCategory'){

                    data.errors = data.errors || [];

                    _.forEach(data.children, function(value, key){
                        if (value.errors){
                            data.errors = data.errors.concat(value.errors);
                        }
                    });
                }

                Form.prototype.showFieldError.call(block, data, field);
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

                block.listenTo(block, 'submit:success', function() {
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