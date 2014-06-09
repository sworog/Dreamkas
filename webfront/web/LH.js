define(function(require, exports, module) {
    //requirements
    var get = require('kit/get/get'),
        config = require('config');

    require('jquery');

    window.LH = _.extend({
        modelNode: require('kit/modelNode/modelNode'),
        collectionNode: require('kit/collectionNode/collectionNode'),
        formatMoney: require('kit/formatMoney'),
        formatAmount: require('kit/formatAmount/formatAmount'),
        formatDate: require('kit/formatDate'),
        isEmptyJSON: require('kit/isEmptyJSON/isEmptyJSON'),
        prevalidateInput: require('kit/prevalidateInput/prevalidateInput'),
        units: require('kit/units/units'),
        productTypes: require('kit/productTypes/productTypes'),
        getText: require('kit/getText'),
        isAllow: function(){
            return true;
        },
        isReportsAllow: function(){
            return true;
        }
    }, config);
});