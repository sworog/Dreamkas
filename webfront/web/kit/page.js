define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        get = require('kit/get/get'),
        _ = require('lodash');

    require('sortable');

    var Page = Block.extend({

        constructor: function(request) {
            var page = this;

            page.el.setAttribute('status', 'loading');

            if (Page.current) {
                Page.current.destroy();
            }

            Page.current = page;

            deepExtend(page, request);

            Promise.resolve(page.get('isAllow')).then(function(isAllow) {
                if (isAllow) {
                    page._initElement();
                    page._initResources();
                    page.initialize.apply(page, arguments);
                    page._startListening();
                } else {
                    page.set('error', 403);
                }
            }, function(error) {
                page.set('error', error);
            });
        },

        el: document.body,
        template: require('tpl!pages/template.ejs'),

        isAllow: true,
        collections: {},
        models: {},

        partials: {
            content: function() {
                return '';
            },
            localNavigation: function() {
                return '';
            },
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation.ejs')
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

        listeners: {
            params: function(params) {
                router.save(params);
            }
        },

        _initResources: function() {

            var page = this;

            page.__collections = page.__collections || page.collections;
            page.__models = page.__models || page.models;

            page.collections = _.transform(page.__collections, function(result, collectionInitializer, key) {
                result[key] = collectionInitializer.extend ? new collectionInitializer : page.get('collections.' + key);
            });

            page.models = _.transform(page.__models, function(result, modelInitializer, key) {
                result[key] = modelInitializer.extend ? new modelInitializer : page.get('models.' + key);
            });
        },

        initialize: function() {
            var page = this;

            Promise.resolve(page.fetch()).then(function() {
                try {
                    page.render();
                    page.el.setAttribute('status', 'loaded');
                } catch (error) {
                    console.error(error);
                }
            }, function(error) {
                console.error(error);
                page.set('error', error);
            });
        },

        render: function(){
            var page = this,
                autofocus,
                firstInput;

            Block.prototype.render.apply(page, arguments);

            autofocus = page.el.querySelector('[autofocus]');
            firstInput = page.el.querySelector('[type=text]');

            Sortable.init();

            if (autofocus){
                autofocus.focus();
            } else if(firstInput) {
                firstInput.focus();
            }

        },

        fetch: function(dataList) {
            var page = this;

            dataList = dataList || _.values(page.collections).concat(_.filter(page.models, function(model) {
                return model && model.id;
            }));

            var fetchList = _.map(dataList, function(data) {
                return (data && typeof data.fetch === 'function') ? data.fetch() : data;
            });

            return Promise.all(fetchList);
        }
    });

    return Page;
});