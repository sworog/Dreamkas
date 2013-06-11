define(function(require) {
        //requirements
        var Tooltip = require('kit/tooltip/tooltip');

        return Tooltip.extend({
            addClass: 'tooltip_editMenu',
            templates: {
                content: require('tpl!./templates/tooltip_editMenu.html')
            }
        });
    }
);