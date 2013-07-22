define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form'),
            PurchaseModel = require('models/purchase');

        return Form.extend({
            blockName: 'form_purchase',
            model: new PurchaseModel(),
            templates: {
                index: require('tpl!blocks/form/form_purchase/templates/index.html')
            },
            onSubmit: function(data) {
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

                Form.prototype.onSubmit.call(this, data);
            },
            onSubmitSuccess: function(){
                alert('Продано!');
                document.location.reload();
            },
            onSubmitError: function(){
                alert('Ошибка!');
            }
        });
    }
);