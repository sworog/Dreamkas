define(function(require, exports, module) {
    //requirements
    var Events = require('../events/events'),
        Uri = require('uri/URI');

    require('lodash');

    // Cached regex for stripping leading and trailing slashes.
    var rootStripper = /^\/+|\/+$/g;

    // Cached regex for stripping a leading hash/slash and trailing space.
    var routeStripper = /^[#\/]|\s+$/g;

    // Cached regex for removing a trailing slash.
    var trailingSlash = /\/$/;

    // Cached regular expressions for matching named param parts and splatted
    // parts of route strings.
    var optionalParam = /\((.*?)\)/g;
    var namesPattern = /[\:\*]([^\:\(\)\?\/]+)/g;
    var namedParam = /(\(\?)?:\w+/g;
    var splatParam = /\*\w+/g;
    var escapeRegExp = /[\-{}\[\]+?.,\\\^$|#\s]/g;

    return window.router = _.extend({
        routes: {},
        root: '/',
        handlers: [],
        history: window.history,
        location: window.location,

        start: function(options) {

            this._bindRoutes();

            options = _.extend({
                silent: false
            });

            if (this.started) {
                throw new Error("Router has already been started");
            }

            this.started = true;

            this.fragment = this.getFragment();

            // Normalize root to always include a leading and trailing slash.
            this.root = ('/' + this.root + '/').replace(rootStripper, '/');

            window.addEventListener('popstate', this.checkUrl.bind(this));

            if (!options.silent) {
                this.loadUrl();
            }

            return this;
        },

        // Disable Backbone.history, perhaps temporarily. Not useful in a real app,
        // but possibly useful for unit testing Routers.
        stop: function() {
            window.removeEventListener('popstate', this.checkUrl);
            this.started = false;
        },

        // Get the cross-browser normalized URL fragment, either from the URL,
        // the hash, or the override.
        getFragment: function(fragment) {
            if (fragment == null) {
                fragment = this.location.pathname + this.location.search;
                var root = this.root.replace(trailingSlash, '');
                if (!fragment.indexOf(root)) fragment = fragment.substr(root.length);
            }

            return fragment.replace(routeStripper, '');
        },

        // Checks the current URL to see if it has changed, and if it has,
        // calls `loadUrl`, normalizing across the hidden iframe.
        checkUrl: function() {

            if (this.getFragment() === this.fragment) {
                return this;
            }

            this.loadUrl();
            return this;
        },

        // Attempt to load the current URL fragment. If a route succeeds with a
        // match, returns `true`. If no defined routes matches the fragment,
        // returns `false`.
        loadUrl: function(fragmentOverride) {

            var router = this;
            var fragment = this.fragment = router.getFragment(fragmentOverride);
            var fragmentPath = fragment.split('?')[0];

            return _.any(router.handlers, function(handler) {
                if (handler.routeRegExp.test(fragmentPath)) {
                    router.handler = handler;
                    handler.callback(fragment);
                    return true;
                }
            });
        },

        // Save a fragment into the hash history, or replace the URL state if the
        // 'replace' option is passed. You are responsible for properly URL-encoding
        // the fragment in advance.
        //
        // The options object can contain `trigger: true` if you wish to have the
        // route callback be fired (not usually desirable), or `replace: true`, if
        // you wish to modify the current URL without adding an entry to the history.
        navigate: function(fragment, options) {

            if (!this.started) {
                return this;
            }

            options = _.extend({
                trigger: true,
                replace: false
            }, options);

            fragment = this.getFragment(fragment || '');

            if (this.fragment === fragment) {
                return this;
            }

            this.fragment = fragment;

            var url = this.root + fragment;

            this.history[options.replace ? 'replaceState' : 'pushState']({}, document.title, url);

            if (options.trigger) {
                this.loadUrl(fragment);
            }

            return this;
        },

        // Manually bind a single named route to a callback. For example:
        //
        //     this.route('search/:query/p:num', 'search', function({params: {query: @string, num: @string}, route: @string}) {
        //       ...
        //     });
        //
        route: function(route, callback) {

            var router = this,
                routeRegExp = this._routeToRegExp(route);

            this.handlers.unshift({
                route: route,
                routeRegExp: routeRegExp,
                callback: function(fragment) {
                    var params = router._extractParameters(route, routeRegExp, fragment);
                    callback && callback.call(router, {
                        params: params,
                        route: route
                    });
                }
            });

            return this;
        },

        save: function(params) {

            if(!_.isPlainObject(params)){
                return;
            }

            var router = this,
                currentParams = router._extractParameters(router.handler.route, router.handler.routeRegExp, router.fragment),
                fragment;

            params = _.extend({}, currentParams, params);

            fragment = router.handler.route
                .replace(optionalParam, '')
                .replace(namedParam, function(match) {
                    var paramName = match.match(namesPattern)[0].substring(1),
                        param = params[paramName];

                    delete params[paramName];

                    return param;
                });

            fragment = new Uri(fragment).setQuery(params).toString();

            router.navigate(fragment, {
                trigger: false
            });
        },

        // Bind all defined routes to router. We have to reverse the
        // order of the routes here to support behavior where the most general
        // routes can be defined at the bottom of the route map.
        _bindRoutes: function() {

            if (!this.routes) {
                return;
            }

            this.routes = _.result(this, 'routes');

            var route, routes = _.keys(this.routes);

            while ((route = routes.pop()) != null) {
                this.route(route, this.routes[route]);
            }
        },

        // Convert a route string into a regular expression, suitable for matching
        // against the current location hash.
        _routeToRegExp: function(route) {
            route = route.replace(escapeRegExp, '\\$&')
                .replace(optionalParam, '(?:$1)?')
                .replace(namedParam, function(match, optional) {
                    return optional ? match : '([^\/]+)';
                })
                .replace(splatParam, '(.*?)');
            return new RegExp('^' + route + '$');
        },

        /**
         * Given a route, and a URL fragment that it matches, return the hash of
         * extracted parameters.
         */
        _extractParameters: function(route, routeRegExp, fragment) {
            var params = routeRegExp.exec(fragment.split('?')[0]).slice(1),
                queryParams = new Uri(fragment).search(true),
                paramNames = _.map(route.match(namesPattern) || [], function(name) {
                    return name.substring(1);
                }),
                namedParams = {};

            _.forEach(params, function(param, i) {
                namedParams[paramNames[i]] = param;
            });

            return _.extend(queryParams, namedParams);
        }
    }, Events);
});