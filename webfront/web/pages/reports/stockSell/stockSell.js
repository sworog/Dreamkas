define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'reports',
        collections: {
            stores: require('resources/store/collection'),
            stockSell: require('resources/stockSell/collection')
        },
        blocks: {
            select_store: require('blocks/select/store/store'),
            inputDateRange: require('blocks/inputDateRange/inputDateRange'),
            table_stockSell: require('blocks/table/stockSell/stockSell')
        }
    });
});