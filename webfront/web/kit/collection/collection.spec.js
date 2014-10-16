define(function(require, exports, module) {
	//requirements
	var Collection = require('./collection'),
		config = require('config');

	describe(module.id, function() {

		var collection,
			initFilters = { a: 1 };

		beforeEach(function() {
			collection = new Collection();
		});

		it('Collection links are equal config links', function() {
			expect(Collection.baseApiUrl).toBe(config.baseApiUrl);
			expect(Collection.mockApiUrl).toBe(config.mockApiUrl);
		});

		it('filter call fetch', function() {
			spyOn(collection, 'fetch');

			collection.filter(initFilters);

			expect(collection.fetch).toHaveBeenCalled();
		});

		it('filter call fetch with right filters', function() {
			var filters;

			spyOn(collection, 'fetch').and.callFake(function() {
				filters = this.filters;
			});

			collection.filter(initFilters);

			expect(filters).toEqual(initFilters);
		});

		it('filter call fetch with right redefined filters', function() {
			var filters;

			spyOn(collection, 'fetch').and.callFake(function() {
				filters = this.filters;
			});

			collection.filters = { a: 2, b: 1 };
			collection.filter(initFilters);

			expect(filters.a).toEqual(initFilters.a);
		});
	});

});
