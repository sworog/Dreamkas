define(function(require) {
    var Block = require('kit/core/block'),
        router = require('router');

    require('backbone');

    return new (Block.extend({
        __name__: 'page',
        el: document.body,
        events: {
            'click .page__tabItem': function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var block = this,
                    $target = $(e.target),
                    rel = $target.attr('rel'),
                    href = $target.attr('href'),
                    $targetContent = $('.page__tabContentItem[rel="' + rel + '"]');

                if (href) {
                    router.navigate(href, {
                        trigger: false
                    });
                }

                $targetContent
                    .addClass('page__tabContentItem_active')
                    .siblings('.page__tabContentItem')
                    .removeClass('page__tabContentItem_active');

                $target
                    .addClass('page__tabItem_active')
                    .siblings('.page__tabItem')
                    .removeClass('page__tabItem_active');
            }
        }
    }));
});