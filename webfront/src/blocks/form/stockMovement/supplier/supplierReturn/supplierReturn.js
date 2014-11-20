define(function(require, exports, module) {
    //requirements
    var Form_supplierStockMovement = require('blocks/form/stockMovement/supplier/supplier');

    return Form_supplierStockMovement.extend({
        template: require('ejs!./template.ejs'),
		id: 'form_supplierReturn',
        model: require('resources/supplierReturn/model')
    });
});