define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Catalog__categoryList = require('blocks/catalog/catalog__categoryList'),
        Tooltip_catalogGroupMenu = require('blocks/tooltip/tooltip_catalogGroupMenu/tooltip_catalogGroupMenu'),
        CatalogCategoriesCollection = require('collections/catalogCategories');

    return Block.extend({
        catalogGroupModel: null,
        blockName: 'catalog__groupItem',
        templates: {
            index: require('tpl!blocks/catalog/templates/catalog__groupItem.html'),
            catalog__categoryList: require('tpl!blocks/catalog/templates/catalog__categoryList.html'),
            catalog__categoryItem: require('tpl!blocks/catalog/templates/catalog__categoryItem.html')
        },
        events: {
            'click .catalog__editGroupLink': function(e){
                e.stopPropagation();
                var block = this,
                    $target = $(e.target);

                block.blocks.tooltip_catalogGroupMenu.show({
                    $trigger: $target,
                    catalogGroupModel: block.catalogGroupModel
                });
            }
        },
        listeners: {
            catalogGroupModel: {
                'change': function(){
                    var block = this;

                    block.render();
                }
            }
        },
        initialize: function() {
            var block = this;

            block.catalogCategoriesCollection = block.catalogGroupModel.categories || new CatalogCategoriesCollection([], {
                parentGroupModel: block.catalogGroupModel
            });

            block.blocks.tooltip_catalogGroupMenu = block.blocks.tooltip_catalogGroupMenu || new Tooltip_catalogGroupMenu();

            Block.prototype.initialize.call(block);

            new Catalog__categoryList({
                catalogCategoriesCollection: block.catalogCategoriesCollection,
                el: block.el.getElementsByClassName('catalog__categoryList')
            });
        }
    });
});