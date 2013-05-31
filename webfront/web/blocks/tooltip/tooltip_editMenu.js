define(
    [
        '/kit/tooltip/tooltip.js',
        './tooltip_editMenu.templates.js'
    ],
    function(Tooltip, templates) {
        return Tooltip.extend({
            addClass: 'tooltip_editMenu',
            templates: templates
        });
    }
);