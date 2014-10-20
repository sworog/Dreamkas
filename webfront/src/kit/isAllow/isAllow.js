define(function(require) {
    //requirements
    var get = require('kit/get/get');

    require('lodash');

    var isAllow = function(permissions, resourcePath, methodList){

        if (typeof permissions === 'string'){
            methodList = resourcePath;
            resourcePath = permissions;
            permissions = {};
        }

        permissions = _.extend({}, isAllow.permissions, permissions);
        methodList = methodList || 'GET';

        var result = false,
            resourcePermissions = get(permissions, resourcePath);

        if (resourcePermissions === 'all'){
            result = true;
        } else if(typeof resourcePermissions === 'string') {
            result = resourcePermissions === methodList;
        }

        if(_.isArray(resourcePermissions) && typeof methodList === 'string'){
            result = _.indexOf(resourcePermissions, methodList) >= 0;
        }

        if(_.isArray(resourcePermissions) && _.isArray(methodList)){
            result = _.intersection(resourcePermissions, methodList).length === methodList.length;
        }

        return result;
    };

    return isAllow;
});