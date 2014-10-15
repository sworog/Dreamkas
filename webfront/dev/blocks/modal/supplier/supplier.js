define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        supplierId: 0,
        id: 'modal_supplier',
        template: require('ejs!./template.ejs'),
        blocks: {
            form_supplier: function(){
                var block = this,
                    Form_supplier = require('blocks/form/supplier/supplier');

                return new Form_supplier({
                    supplierId: block.supplierId
                });
            }
        }
    });
});