define(function(require) {
    //requirements
    var deepExtend = require('./deepExtend'),
        get = require('./get'),
        getChanges = require('./getChanges'),
        pathToObject = require('./pathToObject');

    require('lodash');

    function set(object, path, data, e) {

        var setData;

        if (_.isPlainObject(path)) {
            data = path;
            path = null;

            if (_.isEmpty(data)){
                return;
            }
        }

        if (typeof object['set:' + (path || '*')] === 'function') {
            setData = object['set:' + (path || '*')](data, e);
            data = typeof setData === 'undefined' ? data : setData;
        }

        if (typeof object.trigger === 'function') {
            object.trigger('set' + (path ? ':' + path : ''), _.cloneDeep(data), _.cloneDeep(e));
        }

        if (_.isPlainObject(data)) {
            _.forOwn(data, function(value, pathPart) {
                set(object, (path ? path + '.' : '') + pathPart, value, e);
            });
        } else if (get(object, path) !== data) {
            deepExtend(object, pathToObject(path, data));
        }
    }

    return function(object, path, data, e){

        data = typeof path === 'string' ? pathToObject(path, data) : path;

        return set(object, getChanges(data, object), e);
    }
});