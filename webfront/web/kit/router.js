define(function(require, exports, module) {
    //requirements
    var router = require('bower_components/router/router');

    router._bindRoute = function(route, Page) {

        var router = this,
            routeRegExp = this._routeToRegExp(route);

        this._handlers.unshift({
            route: route,
            routeRegExp: routeRegExp,
            callback: function(fragment) {
                var params = router._extractParameters(route, routeRegExp, fragment);
                Page && new Page({
                    params: params,
                    route: route
                });
            }
        });

        return this;
    };

    return router;
});