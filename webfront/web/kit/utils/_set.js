define(function(require) {
    //requirements
    var deepExtend = require('./deepExtend'),
        pathToObject = require('./pathToObject');

    require('lodash');

    return function set(object, path, data, extra) {

        var keyPath = object,
            setValue;

        if (typeof path === 'string'){
            data = pathToObject(path, data);
            set(object, null, data, extra);
            return;
        }

        extra = deepExtend({
            canceled: false,
            cancel: function() {
                this.canceled = true;
            }
        }, extra);

        _.each(data, function(value, key){

        });

        if(typeof object.trigger === 'function'){
            object.trigger('set:' + path, data);
        }
    }
});