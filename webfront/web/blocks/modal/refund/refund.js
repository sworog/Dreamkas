define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        success: false,
        models: {
            refund: require('resources/refund/model')
        },
        events: {
            'click .modal_refund__reloadLink': function(){
                var block = this;

                block.hide();

                PAGE.render();
            }
        },
        blocks: {
            form_refund: function(){
                var Form_refund = require('blocks/form/refund/refund');

                return Form_refund({
                    model: this.models.refund
                });
            },
            submitButton: function(){
                var SubmitButton = require('./submitButton');

                return new SubmitButton({
                    collection: this.models.refund.collections.products
                });
            }
        },
        reset: function(){
            Modal.prototype.reset.apply(this, arguments);

            this.models.refund.collections.products.each(function(productModel){
                productModel.set('quantity', 0, {
                    silent: true
                });
            });
        }
    });
});