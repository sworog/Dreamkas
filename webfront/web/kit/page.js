define(function(require) {
    //requirements
    var classExtend = require('kit/utils/classExtend'),
        topBar = require('blocks/topBar/topBar'),
        Page403 = require('pages/403/403');

    var $page = $('body');

    var Page = function() {

        var page = this,
            previousPage = $page.data('page'),
            accessDenied = _.some(page.permissions, function(value, key) {
                return !LH.isAllow(key, value);
            });

        if (accessDenied) {
            new Page403();
            return;
        }

        if (previousPage && previousPage.pageName) {
            page.referer = previousPage;
            previousPage.stopListening();
        }

        $page.data('page', page);

        $page
            .removeAttr('class')
            .addClass('page ' + page.pageName)
            .addClass('preloader_spinner');

        page.initialize.apply(page, arguments);
    };

    _.extend(Page.prototype, Backbone.Events, {
        templates: {},
        permissions: {},
        initialize: function() {
            var page = this;
            page.render();
        },
        render: function() {
            var page = this;

            _.each(page.templates, function(template, selector) {
                var $renderContainer = $(selector);

                page.removeBlocks($renderContainer);

                $renderContainer.html(template(page));
            });

            $page.removeClass('preloader_spinner');
        },
        removeBlocks: function($container) {
            var blocks = [];

            $container.find('[block]').each(function() {
                var $block = $(this),
                    __name__ = $block.attr('block');

                blocks.push($block.data(__name__));
            });

            _.each(blocks, function(block) {
                block.remove();
            });
        }
    });

    Page.extend = classExtend;

    return Page;
});