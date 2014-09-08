define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stores',
        collections: {
            stores: require('collections/stores/stores')
        },
		blocks: {
			storeModal: require('blocks/modal/store/store'),
			storeList: require('blocks/storeList/storeList')
		}
    })
});