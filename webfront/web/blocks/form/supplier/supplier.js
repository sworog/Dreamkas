define(function(require, exports, module) {
	//requirements
	var Form = require('blocks/form/form'),
		StoreModel = require('models/store/store');

	return Form.extend({
		template: require('ejs!./template.ejs'),
		model: function() {
			var SupplierModel = require('models/supplier/supplier');

			return PAGE.get('collections.suppliers').get(this.supplierId) || new SupplierModel;
		},
		collection: function() {
			return PAGE.get('collections.suppliers');
		},
		initialize: function() {

			var block = this;

			Form.prototype.initialize.apply(block, arguments);

			var isNew = block.model.isNew();

			block.listenTo(block, 'submit:success', function() {
				if (isNew) {
					block.reset();
				}
			});

		}
	});
});