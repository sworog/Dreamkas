define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        router = require('kit/router/router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        when = require('when'),
        get = require('kit/get/get');

    require('lodash');

    var Page = Block.extend({

        constructor: function(req) {
            var page = this;

            if (Page.current && req.route === Page.current.get('route')) {
                Page.current.set(req);
                return;
            }

            page.set('status', 'starting');

            if (Page.current) {
                Page.current.destroy();
            }

            page.referrer = Page.current;

            Page.current = page;

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

        el: document.body,
        isAllow: true,
        template: require('tpl!kit/page/template.html'),
        templates: {
            content: null,
            localNavigation: null,
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation.html')
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

            window.PAGE = this;

            try {
                page.initResources();
            } catch (error) {
                console.error(error);
            }

            when(page.fetch()).then(function() {
                try {
                    page.render()
                } catch (error) {
                    console.error(error);
                }
            }, function(error) {
                page.set('error', error);
            });
        },
        render: function() {
            var page = this;

            page.set('status', 'rendering');

            if (page.referrer) {
                page.referrer.destroyBlocks();
            }

            Block.prototype.render.apply(page, arguments);

            page.set('status', 'rendered');
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
            if (status == 'starting') {
                page.el.classList.add('preloader_spinner');
            }

            if (status == 'rendered') {
                page.el.classList.remove('preloader_spinner');
            }

            page.el.setAttribute('data-status', status);
        }
    });

    return Page;
});