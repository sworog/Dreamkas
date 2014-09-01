define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: require('models/supplierReturn/supplierReturn'),
        collection: function(){
            return PAGE.get('collections.stockMovements');
        },
        collections: {
            stores: function(){
                return PAGE.get('collections.stores');
            },
            suppliers: function(){
                return PAGE.get('collections.suppliers');
            }
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate'),
            select_suppliers: require('blocks/select/select_suppliers/select_suppliers')
        }
    });
});