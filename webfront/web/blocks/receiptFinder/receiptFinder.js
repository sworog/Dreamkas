define(function(require, exports, module) {
	//requirements
	var Block = require('kit/block/block');

	return Block.extend({
		template: require('ejs!./template.ejs'),
		events: {
			'hide .inputDateRange input[name="dateFrom"]': function(e) {
				this.changeDateRange();
			},
			'hide .inputDateRange input[name="dateTo"]': function() {
				this.changeDateRange();
			}
		},
		collections: {
			receipts: function() {
				return this.receipts;
			}
		},
		blocks: {
			inputDateRange: require('blocks/inputDateRange/inputDateRange'),
			receiptFinder__results: require('./receiptFinder__results')
		},
		changeDateRange: function() {
			var dateFromInput = this.$el.find('.inputDateRange input[name="dateFrom"]'),
				dateToInput = this.$el.find('.inputDateRange input[name="dateTo"]');

			dateFromInput.addClass('loading');
			dateToInput.addClass('loading');

			this.collections.receipts.find(dateFromInput.val(), dateToInput.val()).then(function() {
				dateFromInput.removeClass('loading');
				dateToInput.removeClass('loading');
			});
		}
	});
});