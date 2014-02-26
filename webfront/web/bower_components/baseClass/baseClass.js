define(function(require) {
    //requirements
    var deepExtend = require('./deepExtend'),
        makeClass = require('./makeClass'),
        get = require('./get'),
        set = require('./set');

    require('lodash');

    var BaseClass = function(options){
        deepExtend(this, options);
    };

    return makeClass(BaseClass, {
        get: function(){
            var args = [this].concat([].slice.call(arguments));
            return get.apply(null, args);
        },
        set: function(){
            var args = [this].concat([].slice.call(arguments));
            return set.apply(null, args);
        }
    }, require('./events'));
});