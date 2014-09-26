define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'reports',
		events: {
			'change select[name="store"]': function(e) {
				var page = this,
					select = $(e.target);

				page.productParams.storeId = select.val();
				page.findProducts({ select: select });
			},
			'change select[name="group"]': function(e) {
				var page = this,
					select = $(e.target);

				page.productParams.groupId = select.val() || undefined;
				page.findProducts({ select: select });
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

				collections.storeProducts = new Products()

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
				products = this.collections.storeProducts,
				select = $(params.select);

			products.storeId = page.productParams.storeId;
			params = _.extend({ setParams: true }, params);
			select.addClass('loading');

			return products.filter({ subCategory: page.productParams.groupId }).then(function() {

				select.removeClass('loading');

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
