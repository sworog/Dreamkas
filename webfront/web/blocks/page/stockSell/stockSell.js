define(function(require, exports, module) {
	//requirements
	var Page = require('blocks/page/page');

	return Page.extend({
		activeNavigationItem: 'reports',
		events: {
			'change select[name="store"]': function(e) {
				var storeId = e.target.value || undefined;

				this.setParams({
					storeId: storeId
				});

				e.target.classList.add('loading');

				this.collections.stockSell.fetch({
					filters: {
						store: storeId
					}
				}).then(function() {
					e.target.classList.remove('loading');
				});
			},
			'update .inputDateRange': function(e) {

				var dateFromInput = e.target.querySelector('[name="dateFrom"]'),
					dateToInput = e.target.querySelector('[name="dateTo"]'),
					dateFrom = dateFromInput.value || undefined,
					dateTo = dateToInput.value || undefined;

				this.setParams({
					dateFrom: dateFrom,
					dateTo: dateTo
				});

				dateFromInput.classList.add('loading');
				dateToInput.classList.add('loading');

				this.collections.stockSell.fetch({
					filters: {
						dateFrom: dateFrom,
						dateTo: dateTo
					}
				}).then(function() {
					dateFromInput.classList.remove('loading');
					dateToInput.classList.remove('loading');
				});
			}
		},
		collections: {
			stores: require('resources/store/collection')
		},
		blocks: {
			select_store: require('blocks/select/store/store'),
			inputDateRange: require('blocks/inputDateRange/inputDateRange')
		}
	});
});