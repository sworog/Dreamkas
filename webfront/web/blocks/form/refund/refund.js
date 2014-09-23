define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_refund',
        model: require('models/refund/refund'),
        blocks: {
            inputNumber: function(){
                var block = this,
                    InputNumber = require('blocks/inputNumber/inputNumber'),
                    inputNumber = new InputNumber;

                inputNumber.on({
                    'change': function(value){

                        var refundProductModelCid = this.$el.closest('.modal_refund__position').data('modelCid');

                        block.model.collections.products.get(refundProductModelCid).set('quantity', value);
                    }
                });

                return inputNumber;
            }
        },
        submitSuccess: function(){
            document.getElementById('modal_refund').block.show({
                success: true
            });
        }
    });
});