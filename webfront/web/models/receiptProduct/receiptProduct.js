define(function(require, exports, module) {
	//requirements
	var Model = require('kit/model/model'),
		normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

	return Model.extend({
		defaults: {
			quantity: 1,
			price: null
		},
		models: {
			product: require('models/product/product')
		},
		initialize: function(){
			this.get('price') || this.set('price', this.models.product.get('sellingPrice') || 0)
		},
		saveData: function() {

			return {
				product: this.models.product.id,
				quantity: this.get('quantity'),
				price: normalizeNumber(this.get('price'))
			};
		},
		validate: function(data){
			var validateUrl = Model.baseApiUrl + '/stores/' + PAGE.params.storeId + '/sales?validate=1&validationGroups=products';

			return $.ajax({
				url: validateUrl,
				type: 'POST',
				data: {
					products: [data]
				}
			});
		}
	});
});