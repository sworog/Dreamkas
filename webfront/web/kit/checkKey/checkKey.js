define(function(require, exports, module) {
    //requirements
    var keymap = require('kit/keymap/keymap'),
        _ = require('lodash');

    return function(keycode, keys){
        var keyName = _.findKey(keymap, function(code){
            return code == keycode;
        });

        return _.indexOf(keys, keyName) >= 0;
    }
});