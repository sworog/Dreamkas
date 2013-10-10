define(function(require) {
    //requirements
    var deepExtend = require('./deepExtend'),
        pathToObject = require('./pathToObject');

    require('lodash');

    return function set(object, path, value, extra) {

        var keyPath = object,
            setValue;

        if (typeof path === 'string'){
            value = pathToObject(path, value);
            set(object, null, value, extra);
            return;
        }

        extra = deepExtend({
            canceled: false,
            cancel: function() {
                this.canceled = true;
            }
        }, extra);

        if(typeof object.trigger === 'function'){
            object.trigger('set:' + path, value);
        }
    }
});