define(function(require) {
    //requirements
    var BaseRouter = require('kit/router');

    var Router = BaseRouter.extend({
        routes: {
            //common
            '*path': require('pages/common/login')
        }
    });

    return new Router();
});
