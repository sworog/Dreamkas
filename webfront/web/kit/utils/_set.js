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
            set(object, data, extra);
            return;
        } else {
            extra = data;
            data = path;
        }

        extra = deepExtend({
            canceled: false,
            cancel: function() {
                this.canceled = true;
            }
        }, extra);

        _.each(data, function(value, key){
            if (_.isPlainObject(value)){

            } else {
                object[key] = value;

                if(typeof object.trigger === 'function'){
                    object.trigger('set:' + path, data);
                }
            }
        });
    }
});