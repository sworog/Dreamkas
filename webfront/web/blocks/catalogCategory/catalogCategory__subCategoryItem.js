define(function(require) {
    //requirements
    var Block = require('kit/core/block'),
        Tooltip_catalogSubCategoryMenu = require('blocks/tooltip/tooltip_catalogSubCategoryMenu/tooltip_catalogSubCategoryMenu');

    return Block.extend({
        __name__: 'catalogCategory__subcategoryItem',
        catalogSubCategoryModel: null,
        template: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryItem.html'),
        events: {
            'click .catalog__editSubCategoryLink': function(e){
                e.stopPropagation();
                e.preventDefault();
                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogSubCategoryMenu.show({
                    $trigger: $target,
                    catalogSubCategoryModel: block.catalogSubCategoryModel
                });
            }
        },
        listeners: {
            catalogSubCategoryModel: {
                'destroy': function(){
                    var block = this;

                    block.remove();
                }
            }
        },
        initialize: function(){
            var block = this;

            block.tooltip_catalogSubCategoryMenu = $('[block="tooltip_catalogSubCategoryMenu"]').data('tooltip_catalogSubCategoryMenu') || new Tooltip_catalogSubCategoryMenu()
        }
    });
});