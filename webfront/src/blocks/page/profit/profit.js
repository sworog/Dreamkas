define(function(require, exports, module) {
	//requirements
	var Page = require('blocks/page/page'),
		moment = require('moment');

	return Page.extend({
		activeNavigationItem: 'reports',
		params: {
			dateTo: function(){
				var page = this,
					currentTime = Date.now();

				return page.formatDate(moment(currentTime));
			},
			dateFrom: function(){
				var page = this,
					currentTime = Date.now();

				return page.formatDate(moment(currentTime).subtract(1, 'week'));
			}
		},
		events: {
			'change select[name="store"]': function(e) {
				var store = e.target.value || undefined;

				this.setParams({
					store: store
				});

				e.target.classList.add('loading');

				this.collections.profit.fetch({
					filters: {
						store: store
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

				this.collections.profit.fetch({
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
			stores: require('resources/store/collection'),
			profit: function() {
				var page = this,
                    ProfitCollection = this.ProfitCollection;

				return new ProfitCollection([], {
					groupId: this.params.groupId,
					filters: {
						dateFrom: page.params.dateFrom,
						dateTo: page.formatDate(moment(page.params.dateTo, 'DD.MM.YYYY').add(1, 'days')),
						store: page.params.store
					}
				});
			}
		},

		initData: function(){

			this.params.dateTo = this.get('params.dateTo');
			this.params.dateFrom = this.get('params.dateFrom');

			return Page.prototype.initData.apply(this, arguments);
		},

		blocks: {
			select_store: require('blocks/select/store/store'),
			inputDateRange: require('blocks/inputDateRange/inputDateRange')
		}
	});
});