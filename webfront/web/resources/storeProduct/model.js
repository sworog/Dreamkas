define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        _ = require('lodash');

    return Model.extend({
		models: {
			product: require('resources/product/model'),
			store: require('resources/store/model')
		}
    });
});
