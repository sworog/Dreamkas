define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        supplierId: 0,
        deleted: false,
        id: 'modal_supplier',
        template: require('ejs!./template.ejs'),
        models: {
            supplier: null
        },
        blocks: {
            form_supplier: function(options) {
                var Form_supplier = require('blocks/form/supplier/supplier');

                options.model = this.models.supplier;

                return new Form_supplier(options);
            }
        },
        render: function() {
            var SupplierModel = require('resources/supplier/model');

            this.models.supplier = PAGE.get('collections.suppliers').get(this.supplierId) || new SupplierModel;

            return Modal.prototype.render.apply(this, arguments);
        }
    });
});