define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        template: require('ejs!./form_stockInProducts.ejs'),
        model: require('models/stockInProduct/stockInProduct'),
        collection: function(){
            var block = this;

            return block.get('models.stockIn.collections.products');
        },
        models: {
            stockIn: require('models/stockIn/stockIn')
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
        }
    });
});