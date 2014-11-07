define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
		globalEvents: {
			'submit:success': function(data, block){
				var groupId;

				if (block.el.id === 'form_product'){

					groupId = block.model.get('subCategory.id');

					if (groupId !== PAGE.params.groupId){
						router.navigate('/catalog/groups/' + groupId);
					}
				}
			}
		},
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
