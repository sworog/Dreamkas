define(function(require, exports, module) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        supplierModel = require('models/supplier');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/suppliers',
        el: '.form_supplier',
        model: new supplierModel()
    });
});