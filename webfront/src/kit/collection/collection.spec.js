define(function(require, exports, module) {
	//requirements
	var Collection = require('./collection'),
		config = require('config');

	describe(module.id, function() {

		var collection;

		beforeEach(function() {
			collection = new Collection();
		});

		it('Collection links are equal config links', function() {
			expect(Collection.baseApiUrl).toBe(config.baseApiUrl);
			expect(Collection.mockApiUrl).toBe(config.mockApiUrl);
		});

	});

});
