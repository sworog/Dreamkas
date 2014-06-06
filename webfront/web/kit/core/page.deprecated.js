define(function(require, exports, module) {
    //requirements
    var app = require('app'),
        Block = require('kit/core/block.deprecated'),
        Backbone = require('backbone'),
        router = require('router'),
        isAllow = require('kit/isAllow/isAllow'),
        downloadUrl = require('kit/downloadUrl/downloadUrl'),
        cookies = require('cookies'),
        NewPage = require('kit/page/page');

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
            },
            'click .page__tabItem': function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var block = this,
                    $target = $(e.target),
                    rel = $target.attr('rel'),
                    href = $target.attr('href'),
                    $targetContent = $('.page__tabContentItem[rel="' + rel + '"]');

                if (href) {
                    router.navigate(href, {
                        trigger: false
                    });
                }

                $targetContent
                    .addClass('page__tabContentItem_active')
                    .siblings('.page__tabContentItem')
                    .removeClass('page__tabContentItem_active');

                $target
                    .addClass('page__tabItem_active')
                    .siblings('.page__tabItem')
                    .removeClass('page__tabItem_active');
            }
        },
        constructor: function() {
            var page = this,
                accessDenied;

            this.cid = _.uniqueId('cid');
            this._configure.apply(this, arguments);

            if (window.PAGE){
                window.PAGE.destroy();
            }

            if (accessDenied) {
                router.navigate('/errors/403');

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
        },
        'set:loading': function(loading) {
            var block = this;

            block.el.setAttribute('status', loading ? 'loading' : 'loaded');
        }
    });

    return Page;
});