define(function(require) {
    //requirements
    var deepExtend = require('./deepExtend'),
        pathToObject = require('./pathToObject');

    require('lodash');

    return function set(object, path, data, extra) {
        
        if (_.isPlainObject(path)) {
            data = path;
            path = null;
        }

        if (_.isPlainObject(data)) {
            _.each(data, function(value, pathPart) {
                set(object, (path ? path + '.' : '') + pathPart, value, extra);
            });
        } else {
            deepExtend(object, pathToObject(path, data));
        }
    }
});