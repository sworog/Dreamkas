define(function(require) {
    //requirements
    var classExtend = require('kit/_utils/classExtend'),
        $ = require('jquery'),
        _ = require('underscore');

    var $page = $('body');

    var Page = function() {
        $page.data('page', this);
        this.initialize.apply(this, arguments);
        this.render();
        this.initData();
        this.initBlocks();
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
        initBlocks: function() {
            var page = this;

            _.each(page.blocks, function(init, blockName) {
                if (typeof init === 'function'){
                    page.blocks[blockName] = init.call(page);
                }
            });
        },
        initData: function() {
            var page = this;

            _.each(page.models, function(init, modelName) {
                if (typeof init === 'function') {
                    page.models[modelName] = init.call(page);
                }
            });

            _.each(page.collections, function(init, collectionName) {
                if (typeof init === 'function') {
                    page.collections[collectionName] = init.call(page);
                }
            });
        },
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