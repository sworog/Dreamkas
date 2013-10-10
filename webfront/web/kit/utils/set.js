define(function(require) {
    //requirements
    var deepExtend = require('./deepExtend');

    return function set(object, path, value, extra) {

        var keyPath = object,
            setValue;

        extra = deepExtend({
            canceled: false,
            cancel: function() {
                this.canceled = true;
            }
        }, extra);

        if (_.isObject(path)) {
            set(object, null, path, extra);
            return;
        }

        if (typeof object['set:' + path] === 'function') {
            setValue = object['set:' + path](value, extra);
        }

        if (extra.canceled) {
            extra.canceled = false;
            return;
        }

        if (setValue !== undefined) {
            value = setValue;
        }

        if (_.isObject(value) && !_.isElement(value) && !_.isArray(value)) {
            _.each(value, function(value, pathPart) {
                object.set(path + '.' + pathPart, value, extra);
            });
        } else {
            _.each(path.split('.'), function(pathPart, index, path) {
                if (typeof keyPath[pathPart] == 'undefined') {
                    keyPath[pathPart] = {};
                }

                if (index == (path.length - 1)) {
                    keyPath[pathPart] = value;
                } else {
                    keyPath = keyPath[pathPart];
                }
            });
        }

        object.trigger('set:' + path, value);
    }
});