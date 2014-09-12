define(function(require, exports, module) {
	//requirements
	var Block = require('kit/block/block');

	return Block.extend({
		template: require('ejs!./template.ejs'),
		collections: {
			receipts: require('collections/receipts/receipts')
		},
		blocks: {
			receiptFinder__results: require('./receiptFinder__results')
		}
	});
});