define(function(require, exports, module) {
    //requirements
    var _ = require('lodash');

    return function(string) {

        if (_.isUndefined(string) || _.isNull(string)){
            string = ''
        }

        var normalizedString = string.toString()
            .replace(new RegExp(' ', 'gi'), '')
            .replace(new RegExp(',', 'gi'), '.', 'gi');

        return parseFloat(normalizedString);
    }
});