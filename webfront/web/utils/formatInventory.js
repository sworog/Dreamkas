define(function(require, exports, module) {
    //requirements
    var numeral = require('libs/numeral');

    return function(num){
        return numeral(num).format('0,0.0[00]');
    }
});