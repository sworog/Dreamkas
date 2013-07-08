define(function(require) {
        //requirements
        var Tooltip = require('kit/blocks/tooltip/tooltip');

        return Tooltip.extend({
            blockName: 'tooltip_menu',
            templates: {
                content: require('tpl!blocks/tooltip/tooltip_menu/templates/index.html')
            }
        });
    }
);