define(function(require) {
        //requirements
        var Form = require('blocks/form/form'),
            normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
            formatNumber = require('kit/formatNumber/formatNumber');

        return Form.extend({
            template: require('ejs!./template.ejs'),
            model: require('models/product/product'),
            events: {
                'keyup input[name="purchasePrice"]': function(e){
                    var block = this;

                    block.calculateMarkup();
                },
                'keyup input[name="sellingPrice"]': function(e){
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

                if (block.data.newGroupName.length){

                    block.data.subCategory = {
                        name: block.formData.newGroupName
                    };
                }

                return block.model.save(block.data);
            },
            initialize: function() {

                var block = this;

                Form.prototype.initialize.apply(block, arguments);

                block.calculateMarkup();
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