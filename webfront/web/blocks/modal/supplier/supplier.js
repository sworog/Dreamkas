define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        supplierId: 0,
        template: require('ejs!./template.ejs'),
        blocks: {
            form_supplier: function(){
                var block = this,
                    form_supplier = require('blocks/form/supplier/supplier');

                return new form_supplier({
                    supplierId: block.supplierId
                });
            }
        }
    });
});