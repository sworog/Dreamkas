define(function(require) {
    //requirements
    require('lodash');

    return function(object, path, thisArg){

        switch (typeof object){
            case 'undefined':
                return;
            case 'string':
                thisArg = path;
                path = object;
                object = this;
                break;
        }

        if (typeof object['get:' + path] === 'function'){
            return object['get:' + path]();
        }

        var attr = object,
            segments = path.split('.');

        _.every(segments, function(segment){

            if (typeof attr[segment] === 'function'){
                attr = attr[segment].call(thisArg || object);
            } else {
                attr = attr[segment];
            }

            return attr;
        });

        return attr;
    }
});