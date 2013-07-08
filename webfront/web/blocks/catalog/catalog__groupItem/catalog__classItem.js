define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Catalog__categoryList = require('blocks/catalog/catalog__categoryList/catalog__categoryList'),
        CatalogCategoriesCollection = require('collections/catalogCategories');

    return Block.extend({
        catalogGroupModel: null,
        blockName: 'catalog__groupItem',
        templates: {
            index: require('tpl!blocks/catalog/catalog__groupItem/templates/index.html')
        },
        initialize: function() {
            var block = this;

            Block.prototype.initialize.call(block);

            new Catalog__categoryList({
                catalogCategoriesCollection: block.catalogGroupModel.categories || new CatalogCategoriesCollection([], {
                    parentGroupModel: block.catalogGroupModel
                }),
                el: block.el.getElementsByGroupName('catalog__categoryList')
            });
        }
    });
});