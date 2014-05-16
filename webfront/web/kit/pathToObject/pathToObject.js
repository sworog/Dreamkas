define(function(require) {
    //requirements
    require('lodash');

    return function(path, value){
        var object = {},
            attr = object,
            segments = path.split('.');

        _.each(segments, function(segment, index){
            if (index === segments.length - 1){
                attr[segments[segments.length - 1]] = value;
            } else {
                attr[segment] = {};
            }
            attr = attr[segment];
        });

        return object;
    }
});