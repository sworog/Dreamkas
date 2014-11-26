define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'cashFlows',
        collections: {
            cashFlows: require('resources/cashFlow/collection')
        },
        blocks: {
            modal_cashFlow: require('blocks/modal/cashFlow/cashFlow'),
            table_cashFlows: require('blocks/table/cashFlows/cashFlows')
        }
    });
});
