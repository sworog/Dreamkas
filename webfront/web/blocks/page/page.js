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
                rel = $target.attr('rel');

            var $targetContent = $(".page__tabContentItem[rel='" + rel + "']");
            $targetContent
                .closest(".page__tabsContent")
                .find(".page__tabContentItem")
                .removeClass("page__tabContentItem_active");
            $target
                .closest(".page__tabs")
                .find(".page__tabItem")
                .removeClass("page__tabItem_active");
            $target
                .addClass("page__tabItem_active");
            $targetContent
                .addClass("page__tabContentItem_active");
        }
    });
});