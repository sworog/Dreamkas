define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
        checkKey = require('kit/checkKey/checkKey');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_receiptProduct',
        data: function(){
            var block = this;

            return _.extend(block.model.toJSON(), {
                price: block.model.get('price') ? block.formatMoney(block.model.get('price')) : '',
                quantity: block.formatAmount(block.model.get('quantity'))
            });
        },
        model: require('resources/receiptProduct/model'),
        blocks: {
            inputNumber: function(){
                var block = this,
                    InputNumber = require('blocks/inputNumber/inputNumber'),
                    inputNumber = new InputNumber({
                        value: this.data.quantity
                    });

                inputNumber.on('change', function(value){
                    block.data.quantity = value;
                });

                return inputNumber;
            },
            removeButton: function(){

                var block = this,
                    RemoveButton = require('blocks/removeButton/removeButton');

                return new RemoveButton({
                    model: block.model,
                    removeText: 'Удалить продукт из чека'
                });
            }
        },
        collection: function(){
            return PAGE.models.receipt.collections.products;
        },
        submit: function(){
            var block = this;

            return block.model.validate({
                price: block.data.price.length ? normalizeNumber(block.data.price) : null,
                quantity: block.data.quantity.length ? normalizeNumber(block.data.quantity) : null
            }).then(function(){
                block.model.set(block.data);
            });
        },
        calculateItemPrice: function(){
            var block = this;

            return block.formatMoney(normalizeNumber(block.data.quantity) * normalizeNumber(block.data.price));
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
                block.el.querySelector('[name="' + fieldName + '"]').classList.add('invalid');
            });
        }
    });
});