define(function(require) {
    //requirements
    var Router = require('routers/base');

    return new Router({
        routes: {
            //common
            '*path': require('pages/common/login')
        }
    });
});
