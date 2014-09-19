define(function(require, exports, module) {
    //requirements
	var moment = require('moment');
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        template: require('ejs!./receiptFinder__results.ejs'),
		itemSelector: '.receiptFinder__resultLink',
		isDifferentDates: function(date1, date2) {
			var formatDate = this.formatDate;

			return formatDate(date1) != formatDate(date2);
		}
    });
});