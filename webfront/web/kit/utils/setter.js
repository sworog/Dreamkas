define(function(require) {
    //requirements
    var set = require('../utils/set');

    return {
        set: function(path, value, extra){
            return set(this, path, value, extra);
        }
    }
});