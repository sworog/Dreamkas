define(function(require, exports, module) {
    //requirements
    var Page_productsProfit = require('blocks/page/productsProfit/productsProfit');

    return Page_productsProfit.extend({
        content: require('ejs!./content.ejs'),
        ProfitCollection: require('resources/groupsProfit/collection'),
        blocks: {
            table_groupsProfit: require('blocks/table/groupsProfit/groupsProfit')
        }
    });
});