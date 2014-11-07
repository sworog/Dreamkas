define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stores',
        collections: {
            stores: require('resources/store/collection')
        },
        blocks: {
            modal_store: require('blocks/modal/store/store'),
            storeList: require('blocks/storeList/storeList')
        }
    });
});