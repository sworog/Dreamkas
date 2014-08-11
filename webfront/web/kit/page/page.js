define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        _ = require('lodash');

    require('sortable');
    require('datepicker');
    require('i18n!nls/datepicker');

    var Page = Block.extend({

        el: '.page',
        template: require('ejs!./template.ejs'),

        collections: {},
        models: {},

        activeNavigationItem: 'main',

        content: function() {
            return '<h1>Добро пожаловать в Lighthouse!</h1>';
        },

        initialize: function() {
            var page = this;

            page.setStatus('starting');
            page.setStatus('loading');

            page.collections = _.transform(page.collections, function(result, collectionInitializer, key) {
                result[key] = page.get('collections.' + key);
            });

            page.models = _.transform(page.models, function(result, modelInitializer, key) {
                result[key] = page.get('models.' + key);
            });

            Page.previous = Page.current;
            window.PAGE = Page.current = page;

            $.when(page.fetch()).then(function() {
                page.render();
                page.setStatus('loaded');
            });
        },

        render: function(){
            var page = this,
                autofocus;

            if (Page.previous){
                Page.previous.remove();
            }

            Block.prototype.render.apply(page, arguments);

            autofocus = page.el.querySelector('[autofocus]');

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

            return $.when.apply($, fetchList);
        },

        remove: function(){
            var page = this;

            $('.inputDate, .input-daterange').datepicker('remove');

            Block.prototype.remove.apply(page, arguments);
        },

        setStatus: function(status){
            var page = this;

            page.trigger('status:' + status);

            if (status === 'loading' && Page.current){
                document.body.removeAttribute('status');
            }

            setTimeout(function(){
                document.body.setAttribute('status', status);
            }, 0);
        },

        initBlocks: function(){
            var page = this;

            page.$('button[data-toggle="popover"]').popover({
                trigger: 'focus'
            });

            Sortable.init();

            $('.inputDate, .input-daterange').each(function(){
                $(this).datepicker({
                    language: 'ru',
                    format: 'dd.mm.yyyy',
                    autoclose: true,
                    endDate: this.dataset.endDate && this.dataset.endDate.toString(),
                    todayBtn: "linked"
                });
            });

            Block.prototype.initBlocks.apply(page, arguments);
        }
    });

    return Page;
});