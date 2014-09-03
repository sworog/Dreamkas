define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
        params: {
            productsSortBy: 'name',
            productsSortDirection: 'descending'
        },
        collections: {
            groups: require('collections/groups/groups'),
            products: function(){
                var page = this,
                    ProductsCollection = require('collections/products/products');

                return new ProductsCollection([], {
                    groupId: page.params.groupId
                });
            }
        },
        models: {
            group: function() {
                var page = this,
                    GroupModel = require('models/group/group'),
                    groupModel = new GroupModel({
                        id: page.params.groupId
                    });

                groupModel.on({
                    destroy: function() {
                        var modal = $('.modal:visible')[0];

                        modal.block.hide(function(){
                            router.navigate('/catalog');
                        });

                    }
                });

                return groupModel;
            }
        },
        events: {
            'click .product__link': function(e){
                var page = this,
                    productId = e.currentTarget.dataset.productId;

                $('.modal_product').block.show({
                    models: {
                        product: page.collections.products.get(productId)
                    }
                });

            }
        },
        blocks: {
            modal_product: require('blocks/modal/product/product'),
            modal_group: require('blocks/modal/group/group'),
            productList: require('blocks/productList/productList')
        },
        render: function(){
            var page = this;

            page.collections.sortedProducts = page.collections.products.sortBy(page.params.productsSortBy);

            if (page.params.productsSortDirection === 'descending'){
                page.collections.sortedProducts.reverse();
            }

            Page.prototype.render.apply(page, arguments);
        }
    });
});