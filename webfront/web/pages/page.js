define(function(require) {
    //requirements
    var classExtend = require('kit/utils/classExtend'),
        $ = require('jquery'),
        Backbone = require('backbone'),
        _ = require('underscore'),
        topBar = require('blocks/topBar/topBar'),
        LH = require('LH'),
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

        if (previousPage) {
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
                    blockName = $block.attr('block');

                blocks.push($block.data(blockName));
            });

            _.each(blocks, function(block) {
                block.remove();
            });
        }
    });

    Page.extend = classExtend;

    return Page;
});