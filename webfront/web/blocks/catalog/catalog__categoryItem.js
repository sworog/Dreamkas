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
            'click .catalog__editGroupLink': function(e){
                e.stopPropagation();
                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogGroupMenu.show({
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

            block.tooltip_catalogGroupMenu = $('[block="tooltip_catalogGroupMenu"]').data('tooltip_catalogGroupMenu') || new Tooltip_catalogCategoryMenu()
        }
    });
});