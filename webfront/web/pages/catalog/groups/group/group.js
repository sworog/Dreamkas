define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
        collections: {
            groups: require('resources/group/collection'),
            groupProducts: function(){
                var page = this,
                    ProductsCollection = require('resources/groupProduct/collection');

                return new ProductsCollection([], {
                    groupId: page.params.groupId
                });
            }
        },
        models: {
            group: function() {
                var page = this,
                    GroupModel = require('resources/group/model'),
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
