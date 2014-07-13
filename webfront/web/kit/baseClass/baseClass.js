define(function(require) {
    //requirements
    var deepExtend = require('kit/deepExtend/deepExtend'),
        makeClass = require('kit/makeClass/makeClass'),
        events = require('kit/events/events'),
        get = require('kit/get/get'),
        set = require('kit/set/set');

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
    }, events);
});