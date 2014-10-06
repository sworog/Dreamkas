define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
		id: 'modal_supplierReturn',
		supplierReturnId: null,
        models: {
            supplierReturn: null
        },
        events: {
            'click .supplierReturn__removeLink': function(e) {
                var block = this;

                e.target.classList.add('loading');

                block.models.supplierReturn.destroy().then(function() {
                    e.target.classList.remove('loading');
					block.hide();
                });
            }
        },
        blocks: {
            form_supplierReturn: function(opt) {
                var Form_supplierReturn = require('blocks/form/supplierReturn/supplierReturn');

				opt.model = this.models.supplierReturn;

                return new Form_supplierReturn(opt);
            },
            form_supplierReturnProducts: function(opt) {
                var block = this,
                    Form_stockInProducts = require('blocks/form/supplierReturnProducts/supplierReturnProducts');

				opt.collection = block.models.supplierReturn.collections.products;

                return new Form_stockInProducts(opt);
            }
        },
		render: function(data) {
			var SupplierReturnModel = require('resources/supplierReturn/model');

			this.models.supplierReturn = PAGE.collections.stockMovements.get(data && data.supplierReturnId) || new SupplierReturnModel;

			Modal.prototype.render.apply(this, arguments);
		}
    });
});