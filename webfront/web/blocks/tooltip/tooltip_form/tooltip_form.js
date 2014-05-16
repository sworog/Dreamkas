define(function(require) {
    //requirements
    var Tooltip = require('blocks/tooltip/tooltip.deprecated');

    return Tooltip.extend({
        className: 'tooltip tooltip_form',
        model: null,
        collection: null,
        templates: {
            content: require('tpl!blocks/tooltip/tooltip_catalogSubCategoryForm/templates/content.html')
        },
        show: function(opt) {
            var block = this;

            Tooltip.prototype.show.apply(this, arguments);

            block.initialize();
            block.startListening();

            block.$el.find('[type="text"]').eq(0).focus();
        },
        align: function() {
            var tooltip = this;

            tooltip.$el
                .css({
                    top: tooltip.$trigger.offset().top - (tooltip.$el.outerHeight() - tooltip.$trigger.outerHeight()) / 2,
                    left: tooltip.$trigger.offset().left
                })
        }
    });
});