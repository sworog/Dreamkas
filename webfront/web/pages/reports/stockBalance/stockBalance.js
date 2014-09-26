define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'reports',
        events: {
            'change select[name="store"]': function(e) {
                var page = this,
                    select = e.currentTarget;

                page.$('select[name="group"]').removeAttr('disabled');

                page.findProducts({
                    storeId: e.currentTarget.value
                }).then(function() {
                    select.classList.remove('loading');
                });
            },
            'change select[name="group"]': function(e) {
                var page = this,
                    select = e.currentTarget;

                select.classList.add('loading');

                page.findProducts({
                    groupId: select.value || undefined
                }).then(function() {
                    select.classList.remove('loading');
                });
            }
        },
        collections: {
            stores: require('collections/stores/stores'),
            groups: require('collections/groups/groups'),
            storeProducts: null
        },
        blocks: {
            select_stores: require('blocks/select/stores/stores'),
            select_groupsSimple: require('blocks/select/groupsSimple/groupsSimple'),
            table_stockBalance: require('blocks/table/stockBalance/stockBalance')
        },
        fetch: function() {
            var page = this,
                StoreProductsCollection = require('collections/storeProducts/storeProducts');

            return Page.prototype.fetch.apply(page, arguments).then(function() {

                page.collections.storeProducts = new StoreProductsCollection;

                if (page.collections.stores.length === 1){
                    page.params.storeId = page.collections.stores.at(0).id
                }

                return page.params.storeId && page.findProducts();
            });
        },
        findProducts: function(params) {
            var page = this,
                storeProductsCollection = this.collections.storeProducts;

            params = _.extend({
                storeId: page.params.storeId,
                groupId: page.params.groupId
            }, params);

            storeProductsCollection.storeId = params.storeId;

            page.setParams(params);

            return storeProductsCollection.filter({subCategory: params.groupId});
        }
    });
});
