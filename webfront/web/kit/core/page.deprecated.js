define(function(require, exports, module) {
    //requirements
    var app = require('app'),
        Block = require('kit/core/block.deprecated'),
        Backbone = require('backbone'),
        router = require('router'),
        isAllow = require('kit/utils/isAllow'),
        downloadUrl = require('kit/downloadUrl/downloadUrl'),
        cookies = require('cookies'),
        NewPage = require('page');

    require('lodash');

    var Page = Block.extend({
        moduleId: module.id,
        el: document.body,
        permissions: null,
        referrer: {},
        loading: false,
        template: require('tpl!./template.deprecated.html'),
        partials: {},
        models: {},
        collections: {},
        blocks: {},
        events: {
            'click .page__downloadLink': function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var href = e.target.href;

                if (e.target.classList.contains('preloader_rows')) {
                    return;
                }

                e.target.classList.add('preloader_rows');

                $.ajax({
                    url: href,
                    dataType: 'json',
                    type: 'GET',
                    headers: {
                        Authorization: 'Bearer ' + cookies.get('token')
                    },
                    success: function(res) {
                        downloadUrl(res.url);
                        e.target.classList.remove('preloader_rows');
                    }
                });
            }
        },
        constructor: function() {
            var page = this,
                accessDenied;

            this.cid = _.uniqueId('cid');
            this._configure.apply(this, arguments);

            if (NewPage.current){
                NewPage.current.moduleId = null;
                NewPage.current.destroy();
            }

            switch (typeof page.permissions) {
                case 'object':
                    accessDenied = _.some(page.permissions, function(value, key) {
                        return !isAllow(app.permissions, key, value);
                    });
                    break;
                case 'function':
                    accessDenied = page.permissions();
                    break;
                case 'string':
                    accessDenied = isAllow(app.permissions, page.permissions);
                    break;
            }

            if (accessDenied) {
                router.navigate('/403');

                return;
            }

            this._ensureElement();
            this.initialize.apply(this, arguments);
            this.delegateEvents();
        },
        _configure: function(params, route) {

            Block.prototype._configure.apply(this, arguments);

            var page = this;

            page.route = route;

            if (Page.current) {
                page.referrer = Page.current;
                Page.current.stopListening();
            }

            Page.current = page;
        },
        _ensureElement: function() {

            Block.prototype._ensureElement.apply(this, arguments);

            var page = this;

            page.$el
                .removeClass(page.referrer.__name__)
                .addClass(page.__name__)
                .attr('page', page.__name__);

            page.set('loading', true);
        },
        initialize: function() {
            var page = this;
            page.render();
        },
        render: function() {
            var page = this;

            Block.prototype.render.apply(page, arguments);

            page.set('loading', false);
        },
        save: function(params) {
            var page = this,
                queryParams = Backbone.history.getQueryParameters(),
                pathname;

            page.set(params);

            pathname = page.route
                .replace(/[\(\)]/g, '')
                .replace(/[\:\*]\w+/g, function(placeholder) {

                    var key = placeholder.replace(/[\:\*]/g, ''),
                        string = page[key];

                    delete params[key];
                    delete queryParams[key];

                    return _.isObject(string) ? placeholder : string.toString();
                });

            _.each(_.extend(queryParams, params), function(value, key) {
                if (page.defaults[key] === value) {
                    delete queryParams[key];
                }
            });

            router.navigate(router.toFragment(pathname, queryParams));

            return page;
        }
    });

    return Page;
});