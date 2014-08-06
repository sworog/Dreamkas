define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        formatMoney = require('kit/formatMoney/formatMoney'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
        $ = require('jquery');

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
            'click .delInvoiceProduct': function(e){
                var block = this,
                    modelCid = e.currentTarget.dataset.modelCid;

                block.collection.remove(block.collection.get(modelCid));
            }
        },

        collection: function(){
            var block = this,
                InvoiceProductCollection = require('collections/invoiceProducts/invoiceProducts'),
                invoiceProductCollection = new InvoiceProductCollection();

            invoiceProductCollection.on('add remove', function(){
                block.renderInvoiceProducts();
            });

            return invoiceProductCollection;
        },

        submit: function() {
            return $.when();
        },

        submitSuccess: function() {
            var block = this;

            block.collection.push(_.extend(block.formData, {
                totalPrice: block.getTotalPrice()
            }));

            block.clear();
            block.el.querySelector('[name="productName"]').focus();
        },

        clear: function(){
            var block = this;

            block.$('input[type=text]').val('');
            block.$('[name="productName"]').typeahead('val', '');
            block.renderTotalSum();
        },

        blocks: {
            autocomplete_products: function(){
                var block = this,
                    Autocomplete_products = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
                    autocomplete_products = new Autocomplete_products();

                autocomplete_products.$el.on('typeahead:selected', function(e, product){
                    block.el.querySelector('[name="priceEntered"]').focus();

                    block.renderPriceEntered(product);
                    block.renderQuantity(product);
                    block.renderTotalSum();
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

        renderPriceEntered: function(product) {
            var block = this;

            if (product.purchasePrice){
                block.el.querySelector('[name="priceEntered"]').value = formatMoney(product.purchasePrice);
            }
        },
        renderQuantity: function(product) {
            var block = this;

            block.el.querySelector('[name="quantity"]').value = '1';
            block.$('.invoiceProductForm .product__units').html(product.units || 'шт.');
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