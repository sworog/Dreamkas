define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'reports',
		events: {
			'change select[name="store"]': function(e) {

			}
		},
		collections: {
			stores: require('collections/stores/stores'),
			groups: require('collections/groups/groups')
		},
		blocks: {
			select_stores: require('blocks/select/stores/stores'),
			select_groupsSimple: require('blocks/select/groupsSimple/groupsSimple')
		},
		fetch: function() {
			var block = this,
				Products = require('collections/products/products'),
				products;

			return Page.prototype.fetch.call(block).then(function() {
				var collections = block.collections;
				var stores = collections.stores;

				collections.products = new Products();

				if (stores.length == 1) {
					collections.products.storeId = stores.at(0).get('id');

					return collections.products.findByStore();
				}
			});
		}
    });
});
