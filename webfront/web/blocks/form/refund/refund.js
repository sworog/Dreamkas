define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_refund',
        model: require('resources/refund/model'),
        blocks: {
            inputNumber: function(){
                var block = this,
                    InputNumber = require('blocks/inputNumber/inputNumber'),
                    inputNumber = new InputNumber;

                inputNumber.on({
                    'change': function(value){

                        var refundProductModelCid = this.$el.closest('.form_refund__position').data('modelCid');

                        block.model.collections.products.get(refundProductModelCid).set('quantity', value);
                    }
                });

                return inputNumber;
            }
        },
        submitSuccess: function(){
            document.getElementById('modal_refund').block.show({
                success: true,
				models: {
					refund: this.model
				}
            });
        },
        showFieldError: function(data, field){

            var block = this,
                refundProducts = this.model.getData().products;

            _.forEach(data.children, function(e, productIndex){

                var positionError = '',
                    productId = refundProducts[productIndex].product,
                    $positionElement = block.$('.form_refund__position[data-product-id="' + productId + '"]');

                _.forEach(e.children, function(fieldErrors, name){
                    _.forEach(fieldErrors, function(fieldData){
                        $positionElement.find('[name="' + name + '"]').addClass('invalid');
                        positionError += fieldData.join('. ');
                    })
                });

                if (positionError.length){
                    $positionElement
                        .find('.form__errorMessage')
                        .text(positionError)
                        .addClass('form__errorMessage_visible');
                }
            });
        }
    });
});