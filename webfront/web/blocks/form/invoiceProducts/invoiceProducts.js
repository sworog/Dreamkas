define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: require('resources/invoiceProduct/model'),
        collection: require('resources/invoiceProduct/collection'),
        events: {
            'keyup [name="quantity"]': function(e){
                var block = this;

                block.renderTotalSum();
            },
            'keyup [name="priceEntered"]': function(e){
                var block = this;

                block.renderTotalSum();
            },
            'keyup [name="product.name"]': function(e){
                var block = this;

                if (e.currentTarget.value.length){
                    block.set('data.product', {
                        id: 'xxx'
                    });
                } else {
                    block.set('data.product', {
                        id: null
                    });
                }
            },
            'click .table_invoiceProducts__removeProductLink': function(e){
                var block = this,
                    modelCid = e.currentTarget.dataset.modelCid;

                block.collection.remove(block.collection.get(modelCid));
            }
        },
        blocks: {
            productList: function(){
                var ProductList = require('./productList');

                return new ProductList({
                    collection: this.collection
                });
            },
            autocomplete_products: function() {
                var block = this,
                    ProductAutocomplete = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
                    productAutocomplete;

                productAutocomplete = new ProductAutocomplete({
                    resetLink: false
                });

                productAutocomplete.$el.on('typeahead:selected', function(e, product) {
                    block.selectProduct(product);
                });

                return productAutocomplete;
            }
        },
        submit: function() {
            var block = this;

            return block.collection.validateProduct(block.data);
        },

        submitSuccess: function(invoice) {
            var block = this;

            block.collection.push(invoice.products[0]);

            block.clear();

            block.el.querySelector('[name="product.name"]').focus();
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

                block.el.querySelector('[name="' + fieldName + '"]').classList.add('invalid');
            });
        },

        clear: function(){
            var block = this;

            block.$('input').val('');
            block.$('[name="product.name"]').typeahead('val', '');
            block.renderTotalSum();
        },

        selectProduct: function(product){
            var block = this;

            setTimeout(function(){
                block.el.querySelector('input[name="priceEntered"]').focus();
            }, 0);

            block.set('data.product', {
                id: product.id
            });

            block.el.querySelector('input[name="product.name"]').value = product.name;

            if (product.purchasePrice){
                block.el.querySelector('input[name="priceEntered"]').value = block.formatMoney(product.purchasePrice);
            }

            block.el.querySelector('input[name="quantity"]').value = '1';
            block.$('.invoiceProductForm .product__units').html(product.units || 'шт.');

            block.renderTotalSum();
        },
        renderTotalSum: function(){
            var block = this,
                totalPrice = block.getTotalPrice();

            if (typeof totalPrice === 'number'){
                block.$('.form_invoiceProducts__controls .totalSum').html(totalPrice ? block.formatMoney(totalPrice) : '');
            }
        },
        getTotalPrice: function(){
            var block = this,
                quantity = block.normalizeNumber(block.el.querySelector('input[name="quantity"]').value),
                purchasePrice = block.normalizeNumber(block.el.querySelector('input[name="priceEntered"]').value),
                totalPrice = quantity * purchasePrice;

            return typeof totalPrice === 'number' ? totalPrice : null;
        }
    });
});