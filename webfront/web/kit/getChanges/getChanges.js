define(function(require) {
    //requirements
    require('lodash');

    return function getChanges(newData, oldData){

        var changes = {};

        if (newData === oldData){
            return {};
        }

        if (!_.isPlainObject(newData) || !_.isPlainObject(oldData)){
            return newData;
        }

        _.forOwn(newData, function(value, key){
            if (_.isPlainObject(value)){
                changes[key] = getChanges(value, oldData[key]);

                if (_.isEmpty(changes[key]) && !_.isEmpty(value)){
                    delete changes[key];
                }

            } else if (oldData[key] !== value) {
                changes[key] = value;
            }
        });

        return changes;
    }
});