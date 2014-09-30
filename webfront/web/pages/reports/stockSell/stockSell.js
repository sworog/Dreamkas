define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'reports',
        collections: {
            stores: require('collections/stores/stores'),
            stockSell: require('collections/stockSell/stockSell')
        },
        blocks: {
            select_store: require('blocks/select/store/store'),
            inputDateRange: require('blocks/inputDateRange/inputDateRange'),
            table_stockSell: require('blocks/table/stockSell/stockSell')
        }
    });
});