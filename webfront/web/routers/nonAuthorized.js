define(function(require) {
    //requirements
    var BaseRouter = require('routers/baseRouter');

    var Router = BaseRouter.extend({
        routes: {
            //common
            '*path': require('pages/common/login')
        }
    });

    return new Router();
});
