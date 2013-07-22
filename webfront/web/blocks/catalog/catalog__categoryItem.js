define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu');

    return Block.extend({
        blockName: 'catalog__categoryItem',
        catalogCategoryModel: null,
        templates: {
            index: require('tpl!blocks/catalog/templates/catalog__categoryItem.html')
        },
        events: {
            'click .catalog__editCategoryLink': function(e){
                e.stopPropagation();
                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogCategoryMenu.show({
                    $trigger: $target,
                    catalogCategoryModel: block.catalogCategoryModel
                });
            }
        },
        listeners: {
            catalogCategoryModel: {
                'destroy': function(){
                    var block = this;

                    block.remove();
                }
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.call(block);

            block.tooltip_catalogCategoryMenu = $('[block="tooltip_catalogCategoryMenu"]').data('tooltip_catalogCategoryMenu') || new Tooltip_catalogCategoryMenu()
        }
    });
});