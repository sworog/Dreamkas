define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        template: require('ejs!./form_supplierReturn.ejs'),
        model: require('models/supplierReturn/supplierReturn'),
        collection: function(){
            return PAGE.collections.stockMovements;
        },
        partials: {
            select_suppliers: require('ejs!blocks/select/select_suppliers/template.ejs')
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
            inputDate: require('blocks/inputDate/inputDate')
        }
    });
});