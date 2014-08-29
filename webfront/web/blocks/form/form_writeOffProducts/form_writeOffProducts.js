define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form.deprecated'),
        formatMoney = require('kit/formatMoney/formatMoney'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Form.extend({
        el: '.form_writeOffProducts',

        events: {
            'keyup input[name="quantity"]': function(e){
                var block = this;

                block.renderTotalSum();
            },
            'keyup input[name="price"]': function(e){
                var block = this;

                block.renderTotalSum();
            },
            'keyup input[name="product.name"]': function(e){
                var block = this;

                if (e.currentTarget.value.length){
                    block.el.querySelector('input[name="product.id"]').value = 'xxx';
                } else {
                    block.el.querySelector('input[name="product.id"]').value = null;
                }
            },
            'click .delWriteOffProduct': function(e){
                var block = this,
                    modelCid = e.currentTarget.dataset.modelCid;

                block.collection.remove(block.collection.get(modelCid));
            }
        },

        initialize: function(){
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.collection.on('add remove', function(){
                block.renderWriteOffProducts();
                block.renderWriteIffTotalSum();
            });
        },

        collection: function(){
            var block = this,
                WriteOffProductCollection = require('collections/writeOffProducts/writeOffProducts');

            return new WriteOffProductCollection();
        },

        submit: function() {
            var block = this;

            return block.collection.validateProduct(block.formData);
        },

        submitSuccess: function(writeOff) {
            var block = this;

            block.collection.push(writeOff.products[0]);

            block.clear();
            block.el.querySelector('input[name="product.name"]').focus();
        },

        showErrors: function(error){
            var block = this,
                productErrors = error.errors.children.products.children[0].children;

            var fields = [],
                errorMessages = [];

            _.forEach(productErrors, function(error, field){
                if (error.errors){
                    fields.push(field);
                    errorMessages = _.union(errorMessages, error.errors);
                }
            });

            block.showGlobalError(errorMessages);

            _.forEach(fields, function(fieldName){

                if (fieldName === 'product'){
                    fieldName = 'product.name';
                }

                block.el.querySelector('input[name="' + fieldName + '"]').classList.add('invalid');
            });
        },

        clear: function(){
            var block = this;

            block.$('input').val('');
            block.$('input[name="product.name"]').typeahead('val', '');
            block.renderTotalSum();
        },

        blocks: {
            autocomplete_products: function(){
                var block = this,
                    Autocomplete_products = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
                    autocomplete_products = new Autocomplete_products({
                        el: block.$('.autocomplete_products')
                    });

                autocomplete_products.$el.on('typeahead:selected', function(e, product){
                    block.renderSelectedProduct(product);
                });
            }
        },

        getTotalPrice: function(){
            var block = this,
                quantity = normalizeNumber(block.el.querySelector('input[name="quantity"]').value),
                purchasePrice = normalizeNumber(block.el.querySelector('input[name="price"]').value),
                totalPrice = quantity * purchasePrice;

            return typeof totalPrice === 'number' ? totalPrice : null;
        },

        renderSelectedProduct: function(product){
            var block = this;

            block.el.querySelector('input[name="price"]').focus();
            block.el.querySelector('input[name="product.id"]').value = product.id;
            block.el.querySelector('input[name="product.name"]').value = product.name;

            if (product.purchasePrice){
                block.el.querySelector('input[name="price"]').value = formatMoney(product.purchasePrice);
            }

            block.el.querySelector('input[name="quantity"]').value = '1';
            block.$('.writeOffProductForm .product__units').html(product.units || 'шт.');

            block.renderTotalSum();
        },
        renderTotalSum: function(){
            var block = this,
                totalPrice = block.getTotalPrice();

            if (typeof totalPrice === 'number'){
                block.$('.writeOffProductForm .totalSum').html(totalPrice ? formatMoney(totalPrice) : '');
            }
        },
        renderWriteOffProducts: function(){
            var block = this,
                template = require('ejs!blocks/form/form_writeOffProducts/writeOffProducts.ejs');

            block.$('.table_writeOffProducts tbody').html(template({
                collections: {
                    writeOffProducts: block.collection
                }
            }));
        },
        renderWriteIffTotalSum: function(){
            var block = this,
                totalSum = 0;

            block.collection.forEach(function(productModel){
                totalSum += productModel.get('totalPrice');
            });

            block.$('.writeOff__totalSum').html(formatMoney(totalSum));
        }
    });
});