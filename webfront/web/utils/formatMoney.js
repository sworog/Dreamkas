define(function(require, exports, module) {
    //requirements
    var accounting = require('libs/accounting');

    return function(sum){
        return accounting.formatNumber(sum, 2, ' ', ',');
    }
});