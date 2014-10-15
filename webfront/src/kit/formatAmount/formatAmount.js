define(function(require, exports, module) {
    //requirements
    var numeral = require('numeral');

    return function(amount, format){
        return numeral(amount).format(format || '0,0.0[00]');
    }
});