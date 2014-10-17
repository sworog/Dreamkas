define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
		id: 'form_supplierReturn',
        model: require('resources/supplierReturn/model'),
        collection: function(){
            return PAGE.collections.stockMovements;
        },
        collections: {
            stores: function(){
                return PAGE.collections.stores;
            },
            suppliers: function(){
                return PAGE.collections.suppliers;
            }
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate'),
			select_store: require('blocks/select/store/store'),
			select_supplier: require('blocks/select/supplier/supplier')
        }
    });
});