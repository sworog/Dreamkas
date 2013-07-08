define(function(require) {
        //requirements
        var Tooltip = require('kit/blocks/tooltip/tooltip');

        return Tooltip.extend({
            addClass: 'tooltip_editMenu',
            templates: {
                content: require('tpl!blocks/tooltip/tooltip_editMenu/templates/index.html')
            }
        });
    }
);