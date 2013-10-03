define(function(require) {
    //requirements
    require('lodash');

    return function(depsArray, getter) {

        function computed() {
            var object = this,
                depsAttributes = _.map(depsArray, function(depString) {
                    return object.get(depString);
                });

            return getter.apply(object, depsAttributes);
        }

        computed.__dependencies__ = depsArray;

        return computed;
    }
});