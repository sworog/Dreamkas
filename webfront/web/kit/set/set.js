define(function(require) {
    //requirements
    var deepExtend = require('kit/deepExtend/deepExtend'),
        get = require('kit/get/get'),
        pathToObject = require('kit/pathToObject/pathToObject'),
        getChanges = require('kit/getChanges/getChanges');

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

        if (typeof path === 'string'){
            data = pathToObject(path, data);
        } else {
            extra = data;
            data = path;
        }

        return set(object, null, data, extra);
    }
});