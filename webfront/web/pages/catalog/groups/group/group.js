define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
        collections: {
            groups: require('collections/groups/groups'),
            groupProducts: function(){
                var page = this,
                    ProductsCollection = require('collections/groupProducts/groupProducts'),
                    productCollection = new ProductsCollection([], {
                        groupId: page.params.groupId
                    });

                page.listenTo(productCollection, {
                    remove: function(){
                        var modal = $('.modal:visible');

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
                        router.navigate('/catalog');
                    }
                });

                return groupModel;
            }
        },
        blocks: {
            modal_group: require('blocks/modal/group/group'),
            modal_product: require('blocks/modal/product/product'),
            table_groupProducts: require('blocks/table/groupProducts/groupProducts')
        }
    });
});
