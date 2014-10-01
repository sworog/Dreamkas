define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: function() {
            var SupplierModel = require('models/supplier/supplier');

            return PAGE.get('collections.suppliers').get(this.supplierId) || new SupplierModel;
        },
        collection: function() {
            return PAGE.get('collections.suppliers');
        }
    });
});