define(function(require) {
    //requirements
    var deepExtend = require('bower_components/deepExtend/deepExtend'),
        makeClass = require('bower_components/makeClass/makeClass'),
        get = require('bower_components/get/get'),
        set = require('bower_components/set/set');

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
    }, require('bower_components/events/events'));
});