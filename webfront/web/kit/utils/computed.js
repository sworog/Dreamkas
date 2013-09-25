define(function(require) {
    //requirements
    _ = require('lodash');

    return function(depsStrings, getter) {

        function computed() {
            var object = this,
                depsAttributes = _.map(depsStrings, function(depString) {
                    return object.get(depString);
                });

            return getter.apply(object, depsAttributes);
        }

        computed.__dependencies__ = depsStrings;

        return computed;
    }
});