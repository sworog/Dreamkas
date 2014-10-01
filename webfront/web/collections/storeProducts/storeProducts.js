define(function(require) {
	//requirements
	var Collection = require('kit/collection/collection');

	return Collection.extend({
		model: require('models/storeProduct/storeProduct'),
		storeId: null,
		url: function () {
			return Collection.baseApiUrl + '/stores/' + this.storeId + '/products';
		},
		filters: {
			properties: ['name', 'sku', 'barcode']
		}
	});
});