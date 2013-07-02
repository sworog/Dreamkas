define(function(require) {
    //requirements
    var app = require('app');

    require('routers/authorized');

    app.start();
});