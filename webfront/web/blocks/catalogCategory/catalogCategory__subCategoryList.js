define(function(require) {
    //requirements
    var Block = require('kit/block'),
        CatalogCategory__subCategoryItem = require('blocks/catalogCategory/catalogCategory__subCategoryItem');

    return Block.extend({
        __name__: 'catalogCategory__subCategoryList',
        catalogSubCategoriesCollection: null,
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/catalogCategory__subCategoryList.html'),
            catalogCategory__subCategoryItem: require('tpl!blocks/catalogCategory/templates/catalogCategory__subCategoryItem.html')
        },
        listeners: {
            catalogSubCategoriesCollection: {
                add: function(model, collection, options) {
                    var block = this,
                        catalogCategory__subCategoryItem = new CatalogCategory__subCategoryItem({
                            catalogSubCategoryModel: model
                        });

                    if (collection.length === 1){
                        block.$el.html(catalogCategory__subCategoryItem.el);
                    } else {
                        block.$el.prepend(catalogCategory__subCategoryItem.el);
                    }
                }
            }
        },
        initialize: function(){
            var block = this;

            block.catalogSubCategoriesCollection.each(function(catalogSubCategoryModel){
                new CatalogCategory__subCategoryItem({
                    catalogSubCategoryModel: catalogSubCategoryModel,
                    el: block.el.querySelectorAll('[subCategory_id="' + catalogSubCategoryModel.id + '"]')
                })
            });
        }
    });
});