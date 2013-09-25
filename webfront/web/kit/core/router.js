define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        _ = require('lodash'),
        deepExtend = require('../utils/deepExtend'),
        classExtend = require('../utils/classExtend');

    require('backbone.queryparams');

    Backbone.Router.namedParameters = true;

    var Router = Backbone.Router.extend({
        constructor: function(options){
            var router = this;
            router.defaults = _.clone(router);
            deepExtend(router, options);
            router._bindRoutes();
            router.initialize.apply(router, arguments);
        },
        route: function(route, name, callback) {
            var routeString = route;

            if (!_.isRegExp(route)) route = this._routeToRegExp(route);

            if (_.isFunction(name)) {
                callback = name;
                name = '';
            }
            if (!callback) callback = this[name];
            var router = this;
            Backbone.history.route(route, function(fragment) {

                var params = router._extractParameters(route, fragment)[0];
                callback && callback.call(router, params, routeString);
                router.trigger.apply(router, ['route:' + name].concat(params));
                router.trigger('route', name, params);
                Backbone.history.trigger('route', router, name, params);
            });
            return this;
        }
    });

    Router.extend = classExtend;

    return Router;
});