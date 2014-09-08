define(function(require, exports, module) {
	//requirements
	var Page = require('blocks/page/page');

	return Page.extend({
		content: require('ejs!./content.ejs'),
		activeNavigationItem: 'suppliers',
		collections: {
			suppliers: require('collections/suppliers/suppliers')
		},
		blocks: {
			supplierModal: require('blocks/modal/supplier/supplier'),
			supplierList: require('blocks/supplierList/supplierList')
		}
	})
});