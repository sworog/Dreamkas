define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        ProductList: require('./stockMovementProducts__productList'),
        events: {
            'keyup [name="quantity"], [name="price"], [name="priceEntered"]': function(e) {
                var block = this;

                block.renderTotalSum();
            },
            'click .table_stockMovementProducts__removeProductLink': function(e) {
                var block = this,
                    modelCid = e.currentTarget.dataset.modelCid,
                    product;

                block.collection.remove(block.collection.get(modelCid), {temp: true});
            }
        },
        blocks: {
            productList: function() {
                var ProductList = this.ProductList;

                return new ProductList({
                    collection: this.collection,
                    priceField: this.priceField
                });
            },
            totalPrice: function() {
                var TotalPrice = require('./stockMovementProducts__totalPrice');

                return new TotalPrice({
                    collection: this.collection
                });
            },
            autocomplete_products: function() {
                var block = this,
                    ProductAutocomplete = require('blocks/autocomplete/autocomplete_products/autocomplete_products.deprecated'),
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

            block.collection.push(invoice.products[0], {temp: true});

            block.clear();
            block.el.querySelector('.autocomplete').block.deselect();

            block.$('input[name="product.name"]').focus();
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

                block.$('input[name="' + fieldName + '"]').addClass('invalid');
            });
        },
        clear: function() {
            var block = this;

            block.$('input').val('');
            block.$('[name="product.name"]').typeahead('val', '');
            block.renderTotalSum();
        },
        selectProduct: function(product) {
            var block = this;

            block.set('data.product', {
                id: product.id
            });

            block.$('input[name="product.name"]')
                .val(product.name)
                .focus();

            block.$('input[name="' + block.priceField + '"]').val(product.purchasePrice ? block.formatMoney(product.purchasePrice) : '');

            block.$('input[name="quantity"]').val(1);
            block.$('.product__units').html(product.units || 'шт.');

            block.renderTotalSum();
        },
        deselectProduct: function() {

            var block = this;

            if (block.$('input[name="product.name"]').val().length) {
                block.set('data.product', {
                    id: 'xxx'
                });
            } else {
                block.set('data.product', {
                    id: null
                });
            }

            block.$('input[name="' + block.priceField + '"]').val('');

            block.$('.product__units').html('шт.');

            block.renderTotalSum();

        },
        getTotalSum: function() {
            var block = this,
                quantity = block.normalizeNumber(block.$('input[name="quantity"]').val()),
                purchasePrice = block.normalizeNumber(block.$('input[name="' + this.priceField + '"]').val()),
                totalPrice = quantity * purchasePrice;

            return typeof totalPrice === 'number' ? totalPrice : null;
        },
        renderTotalSum: function() {
            var block = this,
                totalPrice = block.getTotalSum();

            block.$('.totalSum').html(totalPrice ? block.formatMoney(totalPrice) : '');
        }
    });
});