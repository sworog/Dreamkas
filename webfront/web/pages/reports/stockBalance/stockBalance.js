define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'reports',
		events: {
			'change select[name="store"]': function(e) {
				var page = this;

				page.productParams.storeId = $(e.target).val();
				page.findProducts();
			},
			'change select[name="group"]': function(e) {
				var page = this;

				page.productParams.groupId = $(e.target).val();
				page.findProducts();
			}
		},
		productParams: {},
		collections: {
			stores: require('collections/stores/stores'),
			groups: require('collections/groups/groups')
		},
		blocks: {
			select_stores: require('blocks/select/stores/stores'),
			select_groupsSimple: require('blocks/select/groupsSimple/groupsSimple'),
            table_stockBalance: require('blocks/table/stockBalance/stockBalance')
		},
		fetch: function() {
			var page = this,
				Products = require('collections/storeProducts/storeProducts'),
				products;

			return Page.prototype.fetch.call(page).then(function() {
				var collections = page.collections,
					stores = collections.stores,
					promise;

				page.productParams = _.pick(page.params, 'storeId', 'groupId');

				collections.storeProducts = new Products();

				if (page.productParams.storeId) {

					promise = page.findProducts({ setParams: false });

				} else if (stores.length == 1) {

					page.productParams.storeId = stores.at(0).get('id');
					page.setParams(page.productParams);

					promise = page.findProducts({ setParams: false });
				}

				return promise;
			});
		},
		findProducts: function(params) {
			var page = this,
				products = this.collections.storeProducts;

			products.storeId = page.productParams.storeId;

			params = _.extend({ setParams: true }, params || {});

			return products.filter({ subCategory: page.productParams.groupId }).then(function() {
				if (params.setParams)
				{
					page.setParams(page.productParams, {
						render: false
					});
				}
			});
		}
    });
});
