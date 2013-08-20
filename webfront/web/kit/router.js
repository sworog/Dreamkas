define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        classExtend = require('kit/utils/classExtend');

    require('backbone.queryparams');

    Backbone.Router.namedParameters = true;

    var BaseRouter = Backbone.Router.extend({
        routers: {
            '403(/)': require('kit/pages/errors/403')
        }
    });

    BaseRouter.extend = classExtend;

    return BaseRouter;
});