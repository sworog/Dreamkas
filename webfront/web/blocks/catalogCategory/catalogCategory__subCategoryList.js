define(function(require) {
    //requirements
    var Block = require('kit/core/block'),
        CatalogCategory__subcategoryItem = require('blocks/catalogCategory/catalogCategory__subcategoryItem');

    return Block.extend({
        __name__: 'catalogCategory__subcategoryList',
        catalogSubcategoriesCollection: null,

        template: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryList.html'),
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

            block.catalogSubcategoriesCollection.each(function(catalogSubcategoryModel){
                new CatalogCategory__subcategoryItem({
                    catalogSubcategoryModel: catalogSubcategoryModel,
                    el: block.el.querySelectorAll('[subcategory_id="' + catalogSubcategoryModel.id + '"]')
                })
            });
        }
    });
});