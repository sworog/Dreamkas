define(function(require) {
    //requirements
    var BaseClass = require('utils/baseClass'),
        Block = require('block'),
        router = require('router'),
        deepExtend = require('utils/deepExtend'),
        when = require('when'),
        get = require('utils/get');

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

            BaseClass.apply(page, args);

            when(page.get('permission')).then(function(permission){
                if (permission){
                    page.cid = page.get('cid');
                    page.id = page.get('id');
                    page.className = page.get('className');

                    page._initElement();
                    page.initialize.apply(page, args);
                    page.startListening();
                } else {
                    router.navigate('/errors/403', {
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
        template: require('tpl!./template.html'),

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
        initBlocks: function(){
            var page = this;

            Block.prototype.initBlocks.call(page);
        },
        initialize: function() {
            var page = this;

            page.initData();

            when(page.fetch()).then(function(){
                page.render();
            }, function(error) {
                page.set('error', error);
            });
        },
        render: function(){
            var page = this;

            if (page.referrer){
                page.referrer.removeBlocks();
            }

            Block.prototype.render.apply(page, arguments);
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

            page.stopListening();
            page.off();
            page.undelegateEvents();
        },
        'set:error': function(error, extra) {
            var page = this;

            router.navigate('/errors/' + error.status);
        },
        'set:status': function(status){
            var page = this;
            page.el.setAttribute('data-status', status);
        }
    });

    return Page;
});