define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Tooltip_catalogSubCategoryMenu = require('blocks/tooltip/tooltip_catalogSubCategoryMenu/tooltip_catalogSubCategoryMenu');

    return Block.extend({
        __name__: 'catalogCategory__subCategoryItem',
        catalogSubCategoryModel: null,
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/catalogCategory__subCategoryItem.html')
        },
        events: {
            'click .catalog__editSubCategoryLink': 'click .catalog__editSubCategoryLink'
        },
        listeners: {
            catalogSubCategoryModel: {
                'destroy': function(){
                    var block = this;

                    block.remove();
                }
            }
        },
        'click .catalog__editSubCategoryLink': function(e){
            e.stopPropagation();
            e.preventDefault();
            var block = this,
                $target = $(e.target);

            block.tooltip_catalogSubCategoryMenu.show({
                $trigger: $target,
                catalogSubCategoryModel: block.catalogSubCategoryModel
            });
        },
        initialize: function(){
            var block = this;

            block.tooltip_catalogSubCategoryMenu = $('[block="tooltip_catalogSubCategoryMenu"]').data('tooltip_catalogSubCategoryMenu') || new Tooltip_catalogSubCategoryMenu()
        }
    });
});