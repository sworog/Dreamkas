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

            request = _.extend({}, request);

            if (Page.current && Page.current.route === request.route){
                Page.current.set(request, {
                    replace: true
                });
                return;
            }

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
        template: require('ejs!pages/template.ejs'),

        listeners: {
            'change:params': function(params, options){
                router.save(Page.current.params, {
                    replace: options.replace || false
                });

                this.render();
            }
        },

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
            globalNavigation: require('ejs!blocks/globalNavigation/globalNavigation.ejs')
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

            window.PAGE = page;

            Promise.resolve(page.fetch()).then(function() {
                try {
                    page.render();
                    page.trigger('loaded');
                    page.el.setAttribute('status', 'loaded');
                } catch (error) {
                    console.error(error);
                }
            }, function(error) {
                page.set('error', error);
            });
        },

        render: function(){
            var page = this,
                autofocus;

            Block.prototype.render.apply(page, arguments);

            autofocus = page.el.querySelector('[autofocus]');

            Sortable.init();

            if (autofocus){
                setTimeout(function(){
                    autofocus.focus();
                }, 0);
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
        },

        destroy: function(){
            var page = this;

            delete Page.current;

            Block.prototype.destroy.apply(page, arguments);
        }
    });

    return Page;
});