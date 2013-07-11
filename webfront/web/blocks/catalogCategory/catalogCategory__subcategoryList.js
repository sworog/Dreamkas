define(function(require) {
    //requirements
    var Block = require('kit/block'),
        CatalogCategory__subcategoryItem = require('blocks/catalogCategory/catalogCategory__subcategoryItem');

    return Block.extend({
        blockName: 'catalogCategory__subcategoryList',
        catalogSubcategoriesCollection: null,
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryList.html'),
            catalogCategory__subcategoryItem: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryItem.html')
        },
        listeners: {
            catalogSubcategoriesCollection: {
                add: function(model, collection, options) {
                    var block = this,
                        catalogCategory__subcategoryItem = new CatalogCategory__subcategoryItem({
                            catalogSubcategoryModel: model
                        });

                    if (collection.length === 1){
                        block.$el.html(catalogCategory__subcategoryItem.el);
                    } else {
                        block.$el.prepend(catalogCategory__subcategoryItem.el);
                    }
                }
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.call(block);

            block.catalogSubcategoriesCollection.each(function(catalogSubcategoryModel){
                new CatalogCategory__subcategoryItem({
                    catalogSubcategoryModel: catalogSubcategoryModel,
                    el: block.el.querySelectorAll('[subcategory_id="' + catalogSubcategoryModel.id + '"]')
                })
            });
        }
    });
});