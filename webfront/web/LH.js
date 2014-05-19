define(function(require) {
    //requirements

    return window.LH = window.Lighthouse = _.extend({
        isAllow: require('kit/isAllow/isAllow'),
        getText: require('kit/getText/getText'),
        isReportsAllow: require('kit/isReportsAllow/isReportsAllow'),
        modelNode: require('kit/modelNode/modelNode'),
        formatMoney: require('kit/formatMoney/formatMoney'),
        formatAmount: require('kit/formatAmount/formatAmount'),
        formatDate: require('kit/formatDate/formatDate'),
        isEmptyJSON: require('kit/isEmptyJSON/isEmptyJSON'),
        prevalidateInput: require('kit/prevalidateInput/prevalidateInput'),
        units: require('kit/units/units'),
        productTypes: require('kit/productTypes/productTypes')
    }, window.LH, window.Lighthouse);
});