define(function(require, exports, module) {
    //requirements
	var moment = require('moment');
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        collection: require('collections/receipts/receipts'),
        template: require('ejs!./receiptFinder__results.ejs'),
		itemSelector: '.receiptFinder__resultLink',
		isDifferentDates: function(time1, time2) {
			return moment(time1).diff(time2, 'days');
		}
    });
});