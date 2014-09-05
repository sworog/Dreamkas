define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stores',
        models: {
            store: null
        },
        collections: {
            stores: require('collections/stores/stores')
        },
		blocks: {
			modal_store: require('blocks/modal/store/store'),
			storeList: require('blocks/storeList/storeList')
		}
    })
});