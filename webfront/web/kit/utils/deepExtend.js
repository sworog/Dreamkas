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
                        switch (source[prop]){
                            case 'false':
                                source[prop] = false;
                                break;
                            case 'true':
                                source[prop] = true;
                                break;
                        }
                        obj[prop] = source[prop];
                    }
                }
            }
        });

        return obj;
    };
});