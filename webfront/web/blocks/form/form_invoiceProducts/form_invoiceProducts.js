define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        formatMoney = require('kit/formatMoney/formatMoney'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: require('models/invoiceProduct/invoiceProduct'),
        collection: function() {
            var block = this;
            
            return block.get('models.invoice.collections.products');
        },
        models: {
            invoice: require('models/invoice/invoice')
        },
        blocks: {
            invoiceProducts: function(opt){
                var block = this,
                    InvoiceProducts = require('blocks/invoiceProducts/invoiceProducts');

                return new InvoiceProducts({
                    el: opt.el,
                    collection: block.collection
                });
            },
            autocomplete_products: function(opt) {
                var block = this,
                    Autocomplete_products = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
                    autocomplete_products = new Autocomplete_products({
                        el: opt.el
                    });

                autocomplete_products.$el.on('typeahead:selected', function(e, product) {
                    block.renderSelectedProduct(product);
                });

                return autocomplete_products;
            }
        },
        events: {
            'keyup input[name="quantity"]': function(e){
                var block = this;

                block.renderTotalPrice();
            },
            'keyup input[name="priceEntered"]': function(e){
                var block = this;

                block.renderTotalPrice();
            },
            'keyup input[name="product.name"]': function(e){
                var block = this;

                if (e.currentTarget.value.length){
                    block.el.querySelector('[name="product.id"]').value = 'xxx';
                } else {
                    block.el.querySelector('[name="product.id"]').value = null;
                }
            },
            'click .delStockInProduct': function(e){
                var block = this,
                    modelCid = e.currentTarget.dataset.modelCid;

                block.collection.remove(block.collection.get(modelCid));
            }
        },
        getTotalPrice: function() {
            var block = this,
                quantity = normalizeNumber(block.el.querySelector('input[name="quantity"]').value),
                purchasePrice = normalizeNumber(block.el.querySelector('input[name="priceEntered"]').value),
                totalPrice = quantity * purchasePrice;

            return typeof totalPrice === 'number' ? totalPrice : null;
        },
        renderSelectedProduct: function(product){
            var block = this;

            block.el.querySelector('input[name="product.id"]').value = product.id;
            block.el.querySelector('input[name="product.name"]').value = product.name;

            if (product.purchasePrice){
                block.el.querySelector('input[name="priceEntered"]').value = formatMoney(product.purchasePrice);
            }

            block.el.querySelector('input[name="quantity"]').value = '1';
            block.$('.product__units').html(product.units || 'шт.');

            block.renderTotalPrice();

            setTimeout(function(){
                block.el.querySelector('input[name="priceEntered"]').focus();
            }, 0);
        },
        renderTotalPrice: function() {
            var block = this,
                totalPrice = block.getTotalPrice();

            block.$('.totalPrice').html(totalPrice ? formatMoney(totalPrice) : '');
        },
        renderTotalSum: function(){
            var block = this,
                totalSum = 0;

            block.collection.forEach(function(productModel){
                totalSum += productModel.get('totalPrice');
            });

            block.$('.totalSum').html(formatMoney(totalSum));
        },
        submit: function() {
            var block = this;

            return block.collection.validateProduct(block.data);
        },
        submitSuccess: function(invoice) {
            var block = this;

            block.collection.push(invoice.products[0]);

            block.reset();
        },

        showErrors: function(error) {
            var block = this,
                productErrors = error.errors.children.products.children[0].children;

            var fields = [],
                errorMessages = [];

            _.forEach(productErrors, function(error, field) {
                if (error.errors) {
                    fields.push(field);
                    errorMessages = _.union(errorMessages, error.errors);
                }
            });

            block.showGlobalError(errorMessages);

            _.forEach(fields, function(fieldName) {

                if (fieldName === 'product') {
                    fieldName = 'product.name';
                }

                block.el.querySelector('[name="' + fieldName + '"]').classList.add('invalid');
            });
        },
        reset: function(){
            var block = this;

            Form.prototype.reset.apply(block, arguments);

            block.$('[name="product.name"]').typeahead('val', '');
            block.renderTotalPrice();
            block.el.querySelector('[name="product.name"]').focus();

        }
    });
});