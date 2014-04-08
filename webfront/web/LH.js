define(function(require) {
    //requirements
    var isAllow = require('kit/utils/isAllow'),
        translate = require('kit/utils/translate'),
        getText = require('kit/utils/getText'),
        dictionary = require('dictionary'),
        app = require('app');

    getText.dictionary = dictionary;

    return window.LH = window.Lighthouse = _.extend({
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
        formatAmount: require('utils/formatAmount'),
        formatDate: require('utils/formatDate'),
        isEmptyJSON: require('utils/isEmptyJSON'),
        prevalidateInput: require('utils/prevalidateInput'),
        units: require('utils/units')
    }, window.LH, window.Lighthouse);
});