define(function(require) {
    var Block = require("kit/block");

    return Block.extend({
        events: {
            'click .page__tabItem': 'click .page__tabItem'
        },
        'click .page__tabItem': function(e) {
            e.preventDefault();
            var block = this,
                $target = $(e.target),
                rel = $target.attr('rel'),
                $targetContent = $('.page__tabContentItem[rel="' + rel + '"]');

            $targetContent
                .addClass('page__tabContentItem_active')
                .siblings('.page__tabContentItem')
                .removeClass('page__tabContentItem_active');

            $target
                .addClass('page__tabItem_active')
                .siblings('.page__tabItem')
                .removeClass('page__tabItem_active');
            }
    });
});