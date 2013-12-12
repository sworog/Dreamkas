define(function(require, exports, module) {
    //requirements
    var numeral = require('libs/numeral');

    return function(amount){
        return numeral(amount).format('0,0.0[00]');
    }
});