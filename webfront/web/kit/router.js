define(function(require) {
    //requirements
    var classExtend = require('kit/utils/classExtend');

    require('backbone.queryparams');

    var BaseRouter = Backbone.Router.extend({
        routers: {}
    });

    BaseRouter.extend = classExtend;

    return BaseRouter;
});