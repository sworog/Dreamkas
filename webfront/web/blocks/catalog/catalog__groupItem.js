define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Catalog__categoryList = require('blocks/catalog/catalog__categoryList'),
        CatalogCategoriesCollection = require('collections/catalogCategories');

    return Block.extend({
        catalogGroupModel: null,
        blockName: 'catalog__groupItem',
        templates: {
            index: require('tpl!blocks/catalog/templates/catalog__groupItem.html'),
            catalog__categoryList: require('tpl!blocks/catalog/templates/catalog__categoryList.html'),
            catalog__categoryItem: require('tpl!blocks/catalog/templates/catalog__categoryItem.html')
        },
        initialize: function() {
            var block = this;

            block.catalogCategoriesCollection = block.catalogGroupModel.categories || new CatalogCategoriesCollection([], {
                parentGroupModel: block.catalogGroupModel
            });

            Block.prototype.initialize.call(block);

            new Catalog__categoryList({
                catalogCategoriesCollection: block.catalogCategoriesCollection,
                el: block.el.getElementsByClassName('catalog__categoryList')
            });
        }
    });
});