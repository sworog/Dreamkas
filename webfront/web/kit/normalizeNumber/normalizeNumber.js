define(function(require, exports, module) {
    //requirements
    var _ = require('lodash');

    return function(string) {

        if (!string){
            return '';
        }

        var normalizedString = string.toString()
            .replace(' ', '', 'gi')
            .replace(',', '.', 'gi');
        
        return parseFloat(normalizedString);
    }
});