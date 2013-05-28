define(function() {
    return function deepExtend(obj) {
        var slice = Array.prototype.slice,
            hasOwnProperty = Object.prototype.hasOwnProperty;

        _.each(slice.call(arguments, 1), function(source) {
            for (var prop in source) {
                if (hasOwnProperty.call(source, prop)) {
                    if ($.isPlainObject(obj[prop]) && $.isPlainObject(source[prop])) {
                        obj[prop] = deepExtend({}, obj[prop], source[prop]);
                    } else {
                        obj[prop] = source[prop];
                    }
                }
            }
        });

        return obj;
    };
});