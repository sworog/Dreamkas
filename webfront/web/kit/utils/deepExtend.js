define(
    [
        './typeof.js'
    ],
    function(typeOf) {
        return function deepExtend(obj) {
            var slice = Array.prototype.slice,
                hasOwnProperty = Object.prototype.hasOwnProperty;

            _.each(slice.call(arguments, 1), function(source) {
                for (var prop in source) {
                    if (hasOwnProperty.call(source, prop)) {
                        if (typeOf(obj[prop]) === 'object' && typeOf(source[prop]) === 'object') {
                            obj[prop] = deepExtend(obj[prop], source[prop]);
                        } else {
                            obj[prop] = source[prop];
                        }
                    }
                }
            });

            return obj;
        };
    });