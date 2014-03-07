define(function(require) {
    //requirements
    var router = require('router');

    router.routes = {
        //common
        '*path': require('pages/common/login')
    };

    return router;
});
