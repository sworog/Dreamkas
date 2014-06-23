define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        when = require('when'),
        get = require('kit/get/get');

    require('lodash');

    var Page = Block.extend({

        constructor: function(req) {
            var page = this;

            page.set('status', 'loading');

            if (Page.current) {
                Page.current.destroy();
            }

            page.referrer = Page.current;

            Page.current = window.PAGE = page;

            deepExtend(page, req);

            when(page.get('isAllow')).then(function(isAllow) {
                if (isAllow) {
                    page.initElement();
                    page.initialize.apply(page, arguments);
                    page.startListening();
                } else {
                    router.navigate('/errors/403', {
                        replace: true
                    });
                }
            }, function(error) {
                page.set('error', error);
            });
        },

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
        },

        el: document.body,
        isAllow: true,
        template: require('ejs!kit/page/template.html'),
        templates: {
            content: null,
            localNavigation: null,
            globalNavigation: require('ejs!blocks/globalNavigation/globalNavigation.deprecated.html')
        },
        localNavigationActiveLink: null,
        collections: {},
        models: {},

        initResources: function() {

            var page = this;

            page.__collections = page.__collections || page.collections;
            page.__models = page.__models || page.models;

            page.collections = _.transform(page.__collections, function(result, collectionInitializer, key) {
                result[key] = page.get('collections.' + key);
            });

            page.models = _.transform(page.__models, function(result, modelInitializer, key) {
                result[key] = page.get('models.' + key);
            });
        },
        initialize: function() {
            var page = this;

            try {
                page.initResources();
            } catch (error) {
                console.error(error);
            }

            when(page.fetch()).then(function() {
                try {
                    page.render()
                } catch (error) {
                    console.error(error.stack);
                }
            }, function(error) {
                page.set('error', error);
            });
        },
        render: function() {
            var page = this;

            if (page.referrer) {
                page.referrer.destroyBlocks();
            }

            Block.prototype.render.apply(page, arguments);

            page.set('status', 'loaded');
        },
        fetch: function(dataList) {
            var page = this;

            var fetchList = _.map(dataList || page.get('fetchData'), function(data) {
                if (typeof data === 'string') {
                    data = page.get(data);
                }
                return (data && typeof data.fetch === 'function') ? data.fetch() : data;
            });

            return when.all(fetchList);
        },
        fetchData: function() {
            var page = this;

            return _.values(page.collections).concat(_.filter(page.models, function(model) {
                return model && model.id;
            }));
        },
        save: function(params) {
            var page = this;

            page.set('params', params);

            router.save(page.params);

            return page;
        },
        destroy: function() {
            var page = this;

            delete page.referrer;

            Block.prototype.destroy.call(page);
        },
        'set:error': function(error, extra) {
            var page = this;

            router.navigate('/errors/' + error.status, {
                trigger: true
            });
        },
        'set:status': function(status) {
            var page = this;

            page.el.setAttribute('status', status);
        }
    });

    return Page;
});