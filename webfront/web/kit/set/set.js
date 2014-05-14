define(function(require) {
    //requirements
    var deepExtend = require('../deepExtend/deepExtend'),
        get = require('../get/get'),
        pathToObject = require('../pathToObject/pathToObject'),
        getChanges = require('../getChanges/getChanges');

    require('lodash');

    function set(object, path, newData, extra) {

        var oldData = get(object, path),
            changes;

        if (_.isPlainObject(newData)) {
            _.forEach(newData, function(value, pathPart) {
                set(object, (path ? path + '.' : '') + pathPart, value, extra);
            });
        } else {
            deepExtend(object, pathToObject(path, newData));
        }

        if (typeof object['set:' + (path || '*')] === 'function') {
            object['set:' + (path || '*')](newData, extra || {});
        }

        newData = get(object, path);
        changes = getChanges(newData, oldData);

        if (typeof object.trigger === 'function' && !_.isEqual(changes, {})) {
            object.trigger('change' + (path ? ':' + path : ''), _.cloneDeep(newData), _.cloneDeep(extra || {}));
        }
    }

    return function(object, path, data, extra) {

        data = typeof path === 'string' ? pathToObject(path, data) : path;

        return set(object, null, data, extra);
    }
});