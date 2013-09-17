define(function(require) {
    //requirements
    var deepExtend = require('./deepExtend');

    return function(path, value, extra) {
            var object = this,
                keyPath = this,
                setValue;

            extra = deepExtend({
                canceled: false,
                cancel: function() {
                    this.canceled = true;
                }
            }, extra);

            if (_.isObject(path)) {
                _.each(path, function(value, key) {
                    object.set(key, value, extra);
                });
                return;
            }

            if (_.isFunction(object['set:' + path])) {
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