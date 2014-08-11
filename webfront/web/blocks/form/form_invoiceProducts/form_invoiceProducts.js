define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        formatMoney = require('kit/formatMoney/formatMoney'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Form.extend({
        el: '.form_invoiceProducts',

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
                    block.el.querySelector('[name="product.id"]').value = 'xxx';
                } else {
                    block.el.querySelector('[name="product.id"]').value = null;
                }
            },
            'click .delInvoiceProduct': function(e){
                var block = this,
                    modelCid = e.currentTarget.dataset.modelCid;

                block.collection.remove(block.collection.get(modelCid));
            }
        },

        initialize: function(){
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.collection.on('add remove', function(){
                block.renderInvoiceProducts();
            });
        },

        collection: function(){
            var block = this,
                InvoiceProductCollection = require('collections/invoiceProducts/invoiceProducts');

            return new InvoiceProductCollection();
        },

        submit: function() {
            var block = this;

            return block.collection.validateProduct(block.formData);
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

        blocks: {
            autocomplete_products: function(){
                var block = this,
                    Autocomplete_products = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
                    autocomplete_products = new Autocomplete_products();

                autocomplete_products.$el.on('typeahead:selected', function(e, product){
                    block.renderSelectedProduct(product);
                });
            }
        },

        getTotalPrice: function(){
            var block = this,
                quantity = normalizeNumber(block.el.querySelector('[name="quantity"]').value),
                purchasePrice = normalizeNumber(block.el.querySelector('[name="priceEntered"]').value),
                totalPrice = quantity * purchasePrice;

            return typeof totalPrice === 'number' ? totalPrice : null;
        },

        renderSelectedProduct: function(product){
            var block = this;

            block.el.querySelector('[name="priceEntered"]').focus();
            block.el.querySelector('[name="product.id"]').value = product.id;
            block.el.querySelector('[name="product.name"]').value = product.name;

            if (product.purchasePrice){
                block.el.querySelector('[name="priceEntered"]').value = formatMoney(product.purchasePrice);
            }

            block.el.querySelector('[name="quantity"]').value = '1';
            block.$('.invoiceProductForm .product__units').html(product.units || 'шт.');

            block.renderTotalSum();
        },
        renderTotalSum: function(){
            var block = this,
                totalPrice = block.getTotalPrice();

            if (typeof totalPrice === 'number'){
                block.$('.invoiceProductForm .totalSum').html(totalPrice ? formatMoney(totalPrice) : '');
            }
        },
        renderInvoiceProducts: function(){
            var block = this,
                template = require('ejs!blocks/form/form_invoiceProducts/invoiceProducts.ejs');

            block.$('.table_invoiceProducts tbody').html(template({
                collections: {
                    invoiceProducts: block.collection
                }
            }));
        }
    });
});