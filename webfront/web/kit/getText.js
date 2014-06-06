define(function(require, exports, module) {
    //requirements
    var getText = require('kit/getText/getText');

    getText.dictionary = require('i18n!nls/main');

    return getText;
});