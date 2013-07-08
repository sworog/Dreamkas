define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Catalog__categoryItem = require('blocks/catalog/catalog__categoryItem/catalog__categoryItem');

    return Block.extend({
        catalogCategoriesCollection: null,
        templates: {
            index: require('tpl!blocks/catalog/catalog__categoryList/templates/index.html')
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.call(block);

            block.catalogCategoriesCollection.each(function(catalogCategoryModel){
                new Catalog__categoryItem({
                    catalogCategoryModel: catalogCategoryModel,
                    el: block.el.querySelectorAll('[categoryId="' + catalogCategoryModel.id + '"]')
                })
            });
        }
    });
});