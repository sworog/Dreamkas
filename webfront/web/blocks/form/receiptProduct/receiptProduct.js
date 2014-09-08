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
        model: require('models/receiptProduct/receiptProduct'),
        events: {
            'click .form_receiptProduct__quantityPlusLink': function(e){
                e.preventDefault();

                var block = this;

                block.changeQuantity(1);
            },
            'click .form_receiptProduct__quantityMinusLink': function(e){
                e.preventDefault();

                var block = this;

                block.changeQuantity(-1);
            },
            'click .form_receiptProduct__removeLink': function(e){
                var block = this;

                block.model.destroy();

                document.getElementById('modal_receiptProduct').block.hide();
            },
            'keyup [name="quantity"]': function(e){
                e.stopPropagation();

                var block = this;

                if (checkKey(e.keyCode, ['UP'])){
                    block.changeQuantity(1);
                }

                if (checkKey(e.keyCode, ['DOWN'])){
                    block.changeQuantity(-1);
                }
            }
        },
        collection: function(){
            return PAGE.models.receipt.collections.products;
        },
        submit: function(){
            var block = this,
                data = _.extend(block.data, {
                    price: normalizeNumber(block.data.price),
                    quantity: normalizeNumber(block.data.quantity)
                });

            return block.model.validate(data).then(function(){
                block.model.set(data);
            });
        },
        calculateItemPrice: function(){
            var block = this;

            return block.formatMoney(normalizeNumber(block.data.quantity) * normalizeNumber(block.data.price));
        },
        changeQuantity: function(delta){
            var block = this;

            block.data.quantity = block.formatMoney(normalizeNumber(block.data.quantity) + delta);
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