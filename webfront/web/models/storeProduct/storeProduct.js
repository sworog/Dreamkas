define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        _ = require('lodash');

    return Model.extend({
		models: {
			product: require('models/product/product'),
			store: require('models/store/store')
		}
    });
});
