define(function(require, exports, module) {
	//requirements
	var Block = require('kit/block/block'),
		moment = require('moment'),
		formatDate = require('kit/formatDate/formatDate');

	return Block.extend({
		template: require('ejs!./template.ejs'),
		collections: {
			receipts: require('collections/receipts/receipts')
		},
		blocks: {
			inputDateRange: require('blocks/inputDateRange/inputDateRange'),
			receiptFinder__results: require('./receiptFinder__results')
		},
		dateRange: function() {
			var currentTime = Date.now();

			return {
				dateFrom: moment(currentTime).subtract(1, 'week'),
				dateTo: formatDate(currentTime)
			}
		}
	});
});