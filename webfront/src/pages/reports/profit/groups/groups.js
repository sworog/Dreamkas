define(function(require, exports, module) {
    //requirements
    var Page_profit = require('blocks/page/profit/profit');

    return Page_profit.extend({
        content: require('ejs!./content.ejs'),
        ProfitCollection: require('resources/groupsProfit/collection'),
        blocks: {
            table_groupsProfit: require('blocks/table/groupsProfit/groupsProfit')
        }
    });
});