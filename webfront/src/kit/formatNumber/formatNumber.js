define(function(require, exports, module) {
    //requirements
    var numeral = require('numeral');

    return function(number){
        return numeral(number || '').format('0,0.[000]');
    }
});