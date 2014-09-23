define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            refund: require('models/refund/refund')
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
        }
    });
});