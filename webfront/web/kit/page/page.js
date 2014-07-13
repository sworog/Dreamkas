define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        Error = require('blocks/error/error'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
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
                    page.throw('403');
                }
            }, function(error) {
                page.throw(error);
            });
        },

        el: document.body,
        template: require('ejs!./template.ejs'),

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

        content: function() {
            return '<h1>Добро пожаловать в Lighthouse!</h1>';
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
                    page.throw(error);
                }
            }, function(error) {
                page.throw(error);
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

            _.forEach(page.models, function(model){
                model.off();
                model.stopListening();
            });

            _.forEach(page.collections, function(collection){
                collection.off();
                collection.stopListening();
            });

            Block.prototype.destroy.apply(page, arguments);
        },

        throw: function(error){
            new Error({
                jsError: error
            });
        }
    });

    return Page;
});