define(function(require) {
    //requirements
    var app = require('app'),
        userPermissionsModel = require('models/userPermissions');

    require('routers/authorized');

    userPermissionsModel.fetch({
        success: function(){
            app.start();
        }
    });
});