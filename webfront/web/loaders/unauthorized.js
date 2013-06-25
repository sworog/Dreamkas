define(function(require) {
    //requirements
    var app = require('app');

    require('routers/unauthorized');

    app.start();
});