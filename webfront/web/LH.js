define(function(require) {
    //requirements
    var isAllow = require('kit/utils/isAllow'),
        translate = require('kit/utils/translate'),
        getText = require('kit/utils/getText'),
        dictionary = require('dictionary'),
        app = require('app');

    getText.dictionary = dictionary;

    return window.LH = window.Lighthouse = _.extend({
        Block: require('base/block'),
        Page: require('base/page'),
        isAllow: function(resource, method){
            return isAllow(app.permissions, resource, method);
        },
        translate: function(text){
            return translate(dictionary, text);
        },
        getText: getText,
        isReportsAllow: require('utils/isReportsAllow'),
        modelNode: require('kit/utils/modelNode'),
        formatMoney: require('utils/formatMoney'),
        isEmptyJSON: require('utils/isEmptyJSON'),
        prevalidateInput: require('utils/prevalidateInput'),
        units: require('utils/units')
    }, window.LH, window.Lighthouse);
});