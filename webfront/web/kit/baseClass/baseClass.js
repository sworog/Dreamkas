define(function(require) {
    //requirements
    var deepExtend = require('../deepExtend/deepExtend'),
        makeClass = require('../makeClass/makeClass'),
        events = require('../events/events'),
        get = require('../get/get'),
        set = require('../set/set');

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