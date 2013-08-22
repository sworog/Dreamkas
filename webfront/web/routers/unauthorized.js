define(function(require) {
    //requirements
    var Router = require('routers/base');

    return new Router({
        routes: {
            //common
            'login/:test/:testId': require('pages/common/login'),
            '*path': require('pages/common/login')
        }
    });
});
