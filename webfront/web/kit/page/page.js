define(function(require) {
    //requirements
    var Block = require('block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        when = require('when'),
        get = require('kit/get/get');

    require('lodash');

    var Page = Block.extend({

        constructor: function(params) {
            var page = this,
                args = _.toArray(arguments);

            if (Page.current && page.get('cid') === Page.current.get('cid')){
                Page.current.set(params);
                return;
            }

            page.set('status', 'starting');

            if (Page.current) {
                Page.current.destroy();
            }

            page.referrer = Page.current;

            Page.current = page;

            deepExtend(this, params);

            when(page.get('permission')).then(function(permission){
                if (permission){
                    page.initElement();
                    page.initialize.apply(page, args);
                    page.startListening();
                } else {
                    router.navigate('/errors/403', {
                        trigger: true,
                        replace: true
                    });
                }
            }, function(error){
                page.set('error', error);
            });
        },

        el: document.body,
        permission: true,
        className: 'page',
        template: require('tpl!kit/page/template.html'),
        layout: {
            content: function(data){
                return '<h1>API Gate</h1>';
            },
            localNavigation: null,
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation.html')
        },

        initData: function() {

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

            page.initData();

            when(page.fetch()).then(function(){
                try {
                    page.render()
                } catch(error){
                    console.error(error);
                }
            }, function(error) {
                page.set('error', error);
            });
        },
        render: function(){
            var page = this;

            page.set('status', 'rendering');

            if (page.referrer){
                page.referrer.destroyBlocks();
            }

            Block.prototype.render.apply(page, arguments);

            page.set('status', 'rendered');
        },
        fetch: function(dataList) {
            var page = this;

            var fetchList = _.map(dataList || page.get('fetchData'), function(data){
                if (typeof data === 'string'){
                    data = page.get(data);
                }
                return (data && typeof data.fetch === 'function') ? data.fetch() : data;
            });

            return when.all(fetchList);
        },
        fetchData: function(){
            var page = this;

            return _.values(page.collections).concat(_.filter(page.models, function(model){
                return model && model.id;
            }));
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
        'set:status': function(status){
            var page = this;

            if (status == 'rendering'){
                page.el.classList.add('preloader_spinner');
            }

            if (status == 'rendered'){
                page.el.classList.remove('preloader_spinner');
            }

            page.el.setAttribute('data-status', status);
        }
    });

    return Page;
});