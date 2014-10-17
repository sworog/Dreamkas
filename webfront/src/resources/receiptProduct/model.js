define(function(require, exports, module) {
	//requirements
	var Model = require('kit/model/model'),
		normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

	return Model.extend({
		defaults: {
			quantity: 1,
			price: null,
            storeId: function(){
                return PAGE.params.storeId;
            }
		},
		models: {
			product: require('resources/product/model')
		},
		initialize: function(){
			this.get('price') || this.set('price', this.models.product.get('sellingPrice') || 0)
		},
		getData: function() {

			return {
				product: this.models.product.id,
				quantity: this.get('quantity'),
				price: normalizeNumber(this.get('price'))
			};
		},
		validate: function(data){
			var validateUrl = Model.baseApiUrl + '/stores/' + this.get('storeId') + '/sales?validate=1&validationGroups=products';

			return $.ajax({
				url: validateUrl,
				type: 'POST',
				data: {
					products: [_.extend(this.getData(), data)]
				}
			});
		}
	});
});