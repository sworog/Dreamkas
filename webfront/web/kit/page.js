define(function(require) {
    //requirements
    var classExtend = require('kit/_utils/classExtend'),
        $ = require('jquery'),
        _ = require('underscore');

    var $page = $('body');

    var Page = function() {
        var page = this;

        $page.data('page', page);
        page.initialize.apply(page, arguments);

        if (typeof page.initModels === 'function') {
            page.initModels();
        } else {
            _.each(page.initModels, function(initFunction, modelName) {
                if (typeof initFunction === 'function') {
                    page.models[modelName] = initFunction.call(page);
                }
            });
        }

        if (typeof page.initCollections === 'function') {
            page.initCollections();
        } else {
            _.each(page.initCollections, function(initFunction, collectionName) {
                if (typeof initFunction === 'function') {
                    page.collections[collectionName] = initFunction.call(page);
                }
            });
        }

        this.render();

        if (typeof this.initBlocks === 'function') {
            page.initBlocks();
        } else {
            _.each(page.initBlocks, function(initFunction, blockName) {
                if (typeof initFunction === 'function') {
                    page.blocks[blockName] = initFunction.call(page);
                }
            });
        }

        this.fetchData();
    };

    _.extend(Page.prototype, {
        templates: {},
        blocks: {},
        models: {},
        collections: {},
        initialize: function() {
        },
        render: function() {
            var page = this;

            _.each(page.templates, function(template, name) {
                var $renderContainer;

                switch (name) {
                    case 'content':
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
        },
        initBlocks: {},
        initModels: {},
        initCollections: {},
        fetchData: function() {
            var page = this;

            _.each(page.models, function(model) {
                if (model.id && model.url) {
                    model.fetch();
                }
            });

            _.each(page.collections, function(collection) {
                if (collection.url) {
                    collection.fetch();
                }
            });
        }
    });

    Page.extend = classExtend;

    return Page;
});