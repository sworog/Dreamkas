define(function(require) {
    //requirements
    var classExtend = require('kit/utils/classExtend'),
        $ = require('jquery'),
        Backbone = require('backbone'),
        _ = require('underscore'),
        topBar = require('blocks/topBar/topBar');

    var $page = $('body');

    var Page = function() {
        var page = this;

        if ($page.data('page')){
            $page.data('page').stopListening();
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
        initialize: function() {
        },
        render: function() {
            var page = this;

            _.each(page.templates, function(template, name) {
                var $renderContainer;

                switch (name) {
                    case '#content':
                        $renderContainer = $('#content_main');
                        break;
                    default:
                        $renderContainer = $(name);
                        break;
                }

                $renderContainer.children('[block]').each(function() {
                    var $block = $(this),
                        blockName = $block.attr('block');

                    $block.data(blockName).remove();
                });

                $renderContainer.html(template({page: page}));
            });

            $page.removeClass('preloader_spinner');
        }
    });

    Page.extend = classExtend;

    return Page;
});