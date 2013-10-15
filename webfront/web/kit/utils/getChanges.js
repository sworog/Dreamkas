define(function(require) {
    //requirements
    require('lodash');

    return function getChanges(changes, object){

        var changed = {};

        if (!_.isPlainObject(object)){
            return changes;
        }

        _.forOwn(changes, function(value, key){
            if (_.isPlainObject(value)){
                changed[key] = getChanges(value, object[key]);

                if (_.isEmpty(changed[key]) && !_.isEmpty(value)){
                    delete changed[key];
                }

            } else if (object[key] !== value) {
                changed[key] = value;
            }
        });

        return changed;
    }
});