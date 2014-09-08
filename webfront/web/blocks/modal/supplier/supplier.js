define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
		supplierId: 0,
        template: require('ejs!./template.ejs'),
        blocks: {
            supplierForm: require('blocks/form/supplier/supplier')
        }
    });
});