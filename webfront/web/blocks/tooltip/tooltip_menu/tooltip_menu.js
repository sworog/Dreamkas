define(function(require) {
        //requirements
        var Tooltip = require('kit/blocks/tooltip/tooltip');

        return Tooltip.extend({
            className: 'tooltip tooltip_menu',
            templates: {
                content: require('tpl!blocks/tooltip/tooltip_menu/templates/content.html')
            },
            show: function(opt) {
                var block = this;

                Tooltip.prototype.show.apply(this, arguments);

                block.initialize();
                block.startListening();

                block.$el.find('[type="text"]').eq(0).focus();
            }
        });
    }
);