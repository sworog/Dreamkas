define(function(require) {
    //requirements
    require('lodash');

    return function(path){

        var object = this;

        if (typeof object['get:' + path] === 'function'){
            return object['get:' + path]();
        }

        var attr = object,
            segments = path.split('.');

        if (attr === null || typeof attr === 'undefined'){
            return undefined;
        }

        _.every(segments, function(segment){

            if (typeof attr[segment] === 'function'){
                attr = attr[segment].call(object);
            } else {
                attr = attr[segment];
            }

            return attr;
        });

        return attr;
    }
});