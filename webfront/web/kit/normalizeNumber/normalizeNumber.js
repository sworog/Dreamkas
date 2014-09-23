define(function(require, exports, module) {
    //requirements
    var _ = require('lodash');

    return function(string) {

        if (_.isUndefined(string) || _.isNull(string)){
            string = ''
        }

        var normalizedString = string.toString()
            .replace(' ', '', 'gi')
            .replace(',', '.', 'gi');
        
        return parseFloat(normalizedString);
    }
});