define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        checkKey = require('kit/checkKey/checkKey');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'reports',
        events: {
            'change select[name="store"]': function(e) {
                var page = this,
                    select = e.currentTarget;

                page.$('select[name="group"], input[name="productFilter"], .productFinder__resetLink').removeAttr('disabled');

                select.classList.add('loading');

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
            },
            'click .productFinder__resetLink:not([disabled])': function(e){
                var productFilterInput = document.querySelector('[name="productFilter"]');

                productFilterInput.value = '';
                productFilterInput.classList.add('loading');

                this.findProducts({
                    productFilter: undefined
                }).then(function() {
                    productFilterInput.classList.remove('loading');
                });
            },
            'keyup input[name="productFilter"]': function(e){
                var page = this,
                    input = e.target;

                if (checkKey(e.keyCode, ['UP', 'DOWN', 'LEFT', 'RIGHT'])) {
                    return;
                }

                if (checkKey(e.keyCode, ['ESC'])) {
                    input.value = '';
                }

                input.classList.add('loading');

                page.findProducts({
                    productFilter: input.value || undefined
                }).then(function() {
                    input.classList.remove('loading');
                });
            }
        },
        collections: {
            stores: require('resources/store/collection'),
            groups: require('resources/group/collection'),
            storeProducts: null
        },
        blocks: {
            select_store: require('blocks/select/store/store'),
            select_groupsSimple: require('blocks/select/groupsSimple/groupsSimple'),
            table_stockBalance: require('blocks/table/stockBalance/stockBalance')
        },
        fetch: function() {
            var page = this,
                StoreProductsCollection = require('resources/storeProduct/collection');

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
                groupId: page.params.groupId,
                productFilter: page.params.productFilter
            }, params);

            storeProductsCollection.storeId = params.storeId;

            page.setParams(params);

            return storeProductsCollection.filter({
                subCategory: params.groupId,
                query: params.productFilter
            });
        }
    });
});

