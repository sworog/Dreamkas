define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        get = require('kit/get/get'),
        _ = require('lodash');

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
            content: null,
            localNavigation: function() {
                return '';
            },
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation.ejs')
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
            var page = this,
                autofocus,
                firstInput;

            Promise.resolve(page.fetch()).then(function() {
                try {
                    page.render();

                    autofocus = page.el.querySelector('[autofocus]');
                    firstInput = page.el.querySelector('[type=text]');

                    if (autofocus){
                        autofocus.focus();
                    } else if(firstInput) {
                        firstInput.focus();
                    }

                    page.el.setAttribute('status', 'loaded');
                } catch (error) {
                    throw error;
                }
            }, function(error) {
                page.set('error', error);
            });
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