define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        _ = require('lodash');

    var posWindowReference = null; // global variable

    return Block.extend({

        el: '.page',
        template: require('ejs!./template.ejs'),

        activeNavigationItem: 'main',

        events: {
            'click .posLink': function(e) {
                e.preventDefault();

                var page = this;

                page.openPos();
            }
        },

        openPos: function() {
            if (posWindowReference == null || posWindowReference.closed) {
                /* if the pointer to the window object in memory does not exist
                 or if such pointer exists but the window was closed */
                posWindowReference = window.open('/pos', 'pos', 'innerWidth=1000, innerHeight=800');
                /* then create it. The new window will be created and
                 will be brought on top of any other window. */
            } else {
                posWindowReference.focus();
                /* else the window reference must exist and the window
                 is not closed; therefore, we can bring it back on top of any other
                 window with the focus() method. There would be no need to re-create
                 the window or to reload the referenced resource. */
            }
        },

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

        render: function() {
            var page = this,
                autofocus;

            if (window.PAGE && window.PAGE !== page) {
                window.PAGE.remove();
            }

            window.PAGE = page;

            Block.prototype.render.apply(page, arguments);

            autofocus = page.el.querySelector('[autofocus]');

            if (autofocus) {
                setTimeout(function() {
                    autofocus.focus();
                }, 0);
            }

        },

        remove: function() {
            var page = this;

            page.stopListening();
            page.undelegateEvents();

            page.removeBlocks();
        },

        setStatus: function(status) {
            var page = this;

            page.trigger('status:' + status);

            if (status === 'loading' && window.PAGE) {
                document.body.removeAttribute('status');
            }

            setTimeout(function() {
                document.body.setAttribute('status', status);
            }, 0);
        },

        setParams: function(params) {
            var page = this;

            deepExtend(page.params, params);

            router.save(_.transform(page.params, function(result, value, key) {
                result[key] = _.isPlainObject(value) ? JSON.stringify(value) : value;
            }));

            page.render();
        }
    });
});