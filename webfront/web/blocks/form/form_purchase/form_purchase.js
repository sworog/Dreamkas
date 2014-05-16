define(function(require) {
        //requirements
        var Form = require('blocks/form/form'),
            PurchaseModel = require('models/purchase');

        return Form.extend({
            __name__: 'form_purchase',
            model: new PurchaseModel(),
            template: require('tpl!blocks/form/form_purchase/templates/index.html'),
            submit: function(data) {
                var products = [];

                _.each(data.product, function(product, index) {
                    products.push({
                        product: product,
                        quantity: data.quantity[index],
                        sellingPrice: data.sellingPrice[index]
                    });
                });

                data = {
                    products: products
                };

                Form.prototype.submit.call(this, data);
            },
            submitSuccess: function(){
                alert('Продано!');
                document.location.reload();
            },
            submitError: function(){
                alert('Ошибка!');
            }
        });
    }
);