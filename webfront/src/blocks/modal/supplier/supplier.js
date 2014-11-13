define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        supplierId: 0,
        id: 'modal_supplier',
        template: require('ejs!./template.ejs'),
        blocks: {
            form_supplier: function(options) {
                var Form_supplier = require('blocks/form/supplier/supplier');

                options.supplierId = this.supplierId;

                return new Form_supplier(options);
            }
        }
    });
});