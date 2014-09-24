define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        router = require('router'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        cookies = require('cookies'),
        _ = require('lodash');

    var posWindowReference = null,
        previousPage;

    var openPos = function() {

        var posStoreId = cookies.get('posStoreId'),
            posUrl = '/pos' + (posStoreId ? ('/stores/' + posStoreId) : '');

        if (posWindowReference == null || posWindowReference.closed) {
            /* if the pointer to the window object in memory does not exist
             or if such pointer exists but the window was closed */
            posWindowReference = window.open(posUrl, 'pos', 'innerWidth=1000, innerHeight=800');
            /* then create it. The new window will be created and
             will be brought on top of any other window. */
        } else {
            posWindowReference.focus();
            /* else the window reference must exist and the window
             is not closed; therefore, we can bring it back on top of any other
             window with the focus() method. There would be no need to re-create
             the window or to reload the referenced resource. */
        }
    };

    $(document)
        .on('click', '.page__posLink', function(e) {
            e.preventDefault();

            openPos();
        });

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

            Block.prototype.initialize.apply(page, arguments);
        },

        initData: function() {
            var page = this;

            return $.when(Block.prototype.initData.apply(page, arguments)).then(function() {
                return page.fetch();
            });
        },

        render: function() {
            var page = this,
                autofocus;

            if (previousPage) {
                previousPage.remove();
                previousPage = null;
            }

            Block.prototype.render.apply(page, arguments);

            autofocus = page.el.querySelector('[autofocus]');

            if (autofocus) {
                setTimeout(function() {
                    autofocus.focus();
                }, 0);
            }

            page.setStatus('loaded');

        },

        remove: function() {
            var page = this;

            page.unbind();
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

        setParams: function(params, silent) {
            var page = this;

            deepExtend(page.params, params);

            router.save(_.transform(page.params, function(result, value, key) {
                result[key] = _.isPlainObject(value) ? JSON.stringify(value) : value;
            }));

			if (!silent) {
				page.render();
			}
        }
    });
});