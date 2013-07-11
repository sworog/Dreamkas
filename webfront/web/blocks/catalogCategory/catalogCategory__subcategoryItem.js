define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Tooltip_catalogSubcategoryMenu = require('blocks/tooltip/tooltip_catalogSubcategoryMenu/tooltip_catalogSubcategoryMenu');

    return Block.extend({
        blockName: 'catalogCategory__subcategoryItem',
        catalogSubcategoryModel: null,
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryItem.html')
        },
        events: {
            'click .catalog__editSubcategoryLink': function(e){
                e.stopPropagation();
                e.preventDefault();
                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogSubcategoryMenu.show({
                    $trigger: $target,
                    catalogSubcategoryModel: block.catalogSubcategoryModel
                });
            }
        },
        listeners: {
            catalogSubcategoryModel: {
                'destroy': function(){
                    var block = this;

                    block.remove();
                }
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.call(block);

            block.tooltip_catalogSubcategoryMenu = $('[block="tooltip_catalogSubcategoryMenu"]').data('tooltip_catalogSubcategoryMenu') || new Tooltip_catalogSubcategoryMenu()
        }
    });
});