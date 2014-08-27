define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        _ = require('lodash');

    var previousPage;

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

            previousPage = window.PAGE;
            window.PAGE = page;

            page.initData();

            $.when(page.fetch()).then(function() {
                page.render();
                page.setStatus('loaded');
            });
        },

        render: function(){
            var page = this,
                autofocus;

            if (previousPage){
                previousPage.remove();
                previousPage = null;
            }

            Block.prototype.render.apply(page, arguments);

            autofocus = page.el.querySelector('[autofocus]');

            if (autofocus){
                setTimeout(function(){
                    autofocus.focus();
                }, 0);
            }

        },

        remove: function(){
            var page = this;

            page.unbind();
            page.removeBlocks();
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
                result[key] = _.isPlainObject(value) ? JSON.stringify(value) : value;
            }));

            page.render();
        }
    });
});