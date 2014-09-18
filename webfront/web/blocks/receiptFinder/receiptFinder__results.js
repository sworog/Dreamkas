define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        itemSelector: '.receiptFinder__resultLink',
        collection: function(){
            return PAGE.collections.receipts;
        },
        template: require('ejs!./receiptFinder__results.ejs'),
        isDifferentDates: function(date1, date2) {
            return this.formatDate(date1) != this.formatDate(date2);
        }
    });
});