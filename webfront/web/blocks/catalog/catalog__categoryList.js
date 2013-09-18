define(function(require) {
    //requirements
    var Block = require('kit/core/block'),
        Catalog__categoryItem = require('blocks/catalog/catalog__categoryItem');

    return Block.extend({
        __name__: 'catalog__categoryList',
        catalogCategoriesCollection: null,
        template: require('tpl!blocks/catalog/templates/catalog__categoryList.html'),
        templates: {
            index: require('tpl!blocks/catalog/templates/catalog__categoryList.html'),
            catalog__categoryItem: require('tpl!blocks/catalog/templates/catalog__categoryItem.html')
        },
        listeners: {
            catalogCategoriesCollection: {
                add: function(model, collection, options){
                    var block = this,
                        catalogCategoryItem = new Catalog__categoryItem({
                            catalogCategoryModel: model
                        });

                    if (collection.length === 1){
                        block.$el.html(catalogCategoryItem.el);
                    } else {
                        block.$el.prepend(catalogCategoryItem.el);
                    }
                },
                remove: function(model, collection, options){
                    var block = this;

                    if (!collection.length){
                        block.render();
                    }
                }
            }
        },
        initialize: function(){
            var block = this;

            block.catalogCategoriesCollection.each(function(catalogCategoryModel){
                new Catalog__categoryItem({
                    catalogCategoryModel: catalogCategoryModel,
                    el: block.el.querySelectorAll('[category_id="' + catalogCategoryModel.id + '"]')
                })
            });
        }
    });
});