define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        ProductList: require('./stockMovementProducts__productList'),
        events: {
            'keyup [name="price"], [name="priceEntered"]': function(e) {
                var block = this;

                block.renderTotalSum();
            },
            'change .form_stockMovementProducts__controls .inputNumber': function(){
                var block = this;

                block.renderTotalSum();
            },
            'click .table_stockMovementProducts__removeProductLink': function(e) {
                var block = this,
                    modelCid = e.currentTarget.dataset.modelCid,
                    product;

                block.collection.remove(block.collection.get(modelCid));
                block.collection.isChanged = true;
            }
        },
        globalEvents: {
            'hidden': function(data, modal) {
                var block = this,
                    formModal = this.$el.closest('.modal')[0];

                if (!data.submitSuccess && formModal && modal.id == formModal.id) {

                    block.collection.reset(block.originalCollection.models);
                    block.collection.isChanged = false;
                    block.originalCollection = null;
                }
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
            modal_product: require('blocks/modal/product/product'),
            inputNumber: require('blocks/inputNumber/inputNumber'),
            autocomplete_products: function(){

                var block = this,
                    Autocomplete_products = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
                    autocomplete_products = new Autocomplete_products;

                autocomplete_products.on('select', function(productData) {
                    block.selectProduct(productData);
                });

                autocomplete_products.on('deselect', function() {
                    block.deselectProduct();
                });

                return autocomplete_products;
            }
        },
        initialize: function(data){

            this.originalCollection = this.collection.clone();

            return Form.prototype.initialize.apply(this, arguments);
        },
        submit: function() {
            var block = this;

            return block.collection.validateProduct(block.data);
        },
        submitSuccess: function(invoice) {
            var block = this;

            block.collection.push(invoice.products[0]);
            block.collection.isChanged = true;

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

            block.$('input[name="quantity"]').val(block.formatAmount(1));
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
        },
        isChanged: function() {

            return Form.prototype.isChanged.apply(this, arguments) || this.collection.isChanged;
        }
    });
});