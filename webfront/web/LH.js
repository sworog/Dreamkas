define(function(require) {
    //requirements
    var isAllow = require('kit/utils/isAllow'),
        translate = require('kit/utils/translate'),
        dictionary = require('dictionary'),
        app = require('app');

    return window.LH = window.Lighthouse = _.extend({
        Block: require('base/block'),
        Page: require('base/page'),
        isAllow: function(resource, method){
            return isAllow(app.permissions, resource, method);
        },
        translate: function(text){
            return translate(dictionary, text);
        },
        isReportsAllow: require('utils/isReportsAllow'),
        modelNode: require('kit/utils/modelNode'),
        formatPrice: require('utils/formatPrice'),
        formatMoney: require('utils/formatMoney'),
        isEmptyJSON: require('utils/isEmptyJSON'),
        normalizePrice: require('utils/normalizePrice'),
        prevalidateInput: require('utils/prevalidateInput'),
        units: require('utils/units')
    }, window.LH, window.Lighthouse);
});