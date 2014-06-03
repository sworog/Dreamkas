define(function(require, exports, module) {
    //requirements
    var numeral = require('numeral');

    numeral.language('root', require('i18n!nls/numeral'));
    numeral.language('root');

    return numeral;
});