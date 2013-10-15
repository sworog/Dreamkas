define(function(require) {
    //requirements
    var get = require('./get');

    require('lodash');

    return function(permissions, resourcePath, methodList){

        methodList = methodList || 'GET';

        var isAllow = false,
            resourcePermissions = get(permissions, resourcePath);

        if (resourcePermissions === 'all'){
            isAllow = true;
        } else if(typeof resourcePermissions === 'string') {
            isAllow = resourcePermissions === methodList;
        }

        if(_.isArray(resourcePermissions) && typeof methodList === 'string'){
            isAllow = _.indexOf(resourcePermissions, methodList) >= 0;
        }

        if(_.isArray(resourcePermissions) && _.isArray(methodList)){
            isAllow = _.intersection(resourcePermissions, methodList).length === methodList.length;
        }

        return isAllow;
    }
});