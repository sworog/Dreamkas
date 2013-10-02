define(function(require) {
    //requirements
    var _ = require('lodash');

    return function(){

        var object = arguments[0],
            path = arguments[1],
            params;

        if (typeof object === 'string'){
            path = object;
            object = this;
            params = Array.prototype.slice.call(arguments, 1);
        } else {
            params = Array.prototype.slice.call(arguments, 2);
        }

        if (typeof object['get:' + path] === 'function'){
            return object['get:' + path].apply(object, params);
        }

        var attr = object,
            segments = path.split('.');

        if (attr === null || typeof attr === 'undefined'){
            return undefined;
        }

        _.every(segments, function(segment){

            if (typeof attr[segment] === 'function'){
                attr = attr[segment].apply(attr, params);
            } else {
                attr = attr[segment];
            }

            return attr;
        });

        return attr;
    }
});