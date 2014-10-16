define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form.deprecated'),
        SupplierModel = require('resources/supplier/model');

    return Form.extend({
        el: '.form_supplier',
        model: function(){
            return new SupplierModel();
        },
        initialize: function() {

            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.listenTo(block, 'submit:success', function() {
                if (!block.__model.id) {
                    block.model = new SupplierModel();
                }
            });
        }
    });
});