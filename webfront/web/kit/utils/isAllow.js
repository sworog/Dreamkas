define(function(require) {
    //requirements
    var get = require('./get');

    return function(permissions, resourcePath, method){

        method = method || 'GET';

        var isAllow = false,
            resourcePermissions = get(permissions, resourcePath);

        if (resourcePermissions === 'all'){
            isAllow = true;
        } else if(typeof resourcePermissions === 'string') {
            isAllow = resourcePermissions === method;
        } else if(resourcePermissions){
            isAllow = _.indexOf(resourcePermissions, method) >= 0;
        }

        return isAllow;
    }
});