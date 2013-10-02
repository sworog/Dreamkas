define(function(require) {
    //requirements
    var _ = require('lodash');

    return function(){
        
        var args = [].slice.call(arguments);
        
        args.push(function(objectValue, sourceValue){
            return _.isArray(sourceValue) ? sourceValue : undefined;
        });

        return _.merge.apply(this, args);
    }
});