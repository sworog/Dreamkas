define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        _ = require('lodash');

    require('sortable');
    require('datepicker');
    require('i18n!nls/datepicker');

    return Block.extend({

        el: '.page',
        template: require('ejs!./template.ejs'),

        activeNavigationItem: 'main',

        content: function() {
            return '<h1>Добро пожаловать в Lighthouse!</h1>';
        },

        initialize: function() {
            var page = this;

            page.setStatus('starting');
            page.setStatus('loading');

            page.initResources();

            $.when(page.fetch()).then(function() {
                page.render();
                page.setStatus('loaded');
            });
        },

        render: function(){
            var page = this,
                autofocus;

            if (window.PAGE && window.PAGE !== page){
                window.PAGE.destroy();
            }

            window.PAGE = page;

            Block.prototype.render.apply(page, arguments);

            autofocus = page.el.querySelector('[autofocus]');

            if (autofocus){
                setTimeout(function(){
                    autofocus.focus();
                }, 0);
            }

        },

        destroy: function(){
            var page = this;

            $('.inputDate, .input-daterange').datepicker('remove');

            Block.prototype.destroy.apply(page, arguments);

        },

        setStatus: function(status){
            var page = this;

            page.trigger('status:' + status);

            if (status === 'loading' && window.PAGE){
                document.body.removeAttribute('status');
            }

            setTimeout(function(){
                document.body.setAttribute('status', status);
            }, 0);
        },

        setParams: function(params){
            var page = this;

            deepExtend(page.params, params);

            router.save(_.transform(page.params, function(result, value, key){
                try {
                    result[key] = JSON.stringify(value);
                } catch(e) {
                    result[key] = value;
                }
            }));

            page.render();
        },

        initBlocks: function(){
            var page = this;

            Block.prototype.initBlocks.apply(page, arguments);

            Sortable.init();

            page.$('button[data-toggle="popover"]').popover({
                trigger: 'focus'
            });

            page.$('.inputDate, .input-daterange').each(function(){
                $(this).datepicker({
                    language: 'ru',
                    format: 'dd.mm.yyyy',
                    autoclose: true,
                    endDate: this.dataset.endDate && this.dataset.endDate.toString(),
                    todayBtn: "linked"
                });
            });
        }
    });
});