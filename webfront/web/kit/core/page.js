define(function(require) {
    //requirements
    var app = require('app'),
        Block = require('./block'),
        Backbone = require('backbone'),
        router = require('router'),
        isAllow = require('../utils/isAllow');

    require('lodash');

    var Page = Block.extend({
        el: document.body,
        permissions: null,
        referrer: {},
        loading: false,
        partials: {},
        models: {},
        collections: {},
        blocks: {},
        constructor: function(){

            var page = this,
                accessDenied;

            this.cid = _.uniqueId('cid');
            this._configure.apply(this, arguments);

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

            Backbone.View.prototype._ensureElement.apply(this, arguments);

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

            _.each(page.partials, function(template, selector) {
                var $renderContainer = $(selector);

                page.removeBlocks($renderContainer);

                $renderContainer.html(template(page));
            });

            page.set('loading', false);
        },
        removeBlocks: function($container) {
            var blocks = [];

            $container.find('[block]').each(function() {
                var $block = $(this),
                    __name__ = $block.attr('block');

                blocks.push($block.data(__name__));
            });

            _.each(blocks, function(block) {
                block.remove();
            });
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