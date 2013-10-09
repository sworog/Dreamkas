define(function(require) {
    //requirements
    var isAllow = require('kit/utils/isAllow'),
        text = require('kit/utils/text'),
        dictionary = require('dictionary'),
        app = require('app');

    return window.LH = window.Lighthouse = _.extend({
        isAllow: function(resource, method){
            return isAllow(app.permissions, resource, method);
        },
        text: function(text){
            return text(dictionary, text);
        },
        modelNode: require('kit/utils/modelNode'),
        formatPrice: require('utils/formatPrice'),
        isEmptyJSON: require('utils/isEmptyJSON'),
        normalizePrice: require('utils/normalizePrice'),
        prevalidateInput: require('utils/prevalidateInput'),
        units: require('utils/units')
    }, window.LH, window.Lighthouse);
});