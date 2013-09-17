define(function(require) {
    //requirements
    var app = require('app');

    return function(resource, method){

        method = method || 'GET';

        var isAllow = false,
            resourcePermissions = app.permissions[resource];

        if (resourcePermissions && resourcePermissions === 'all'){
            isAllow = true;
        } else if(typeof resourcePermissions === 'string') {
            isAllow = resourcePermissions === method;
        } else if(resourcePermissions){
            isAllow = _.indexOf(resourcePermissions, method) >= 0;
        }

        return isAllow;
    }
});