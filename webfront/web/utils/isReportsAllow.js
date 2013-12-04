define(function(require, exports, module) {
    //requirements
    var currentUserModel = require('models/currentUser');

    require('lodash');

    return function(reports){
        var reportsPermissionsMap = {
            storeGrossSalesByHours: LH.isAllow('stores', 'GET::{store}/reports/grossSalesByHours') && currentUserModel.stores && currentUserModel.stores.length,
            storeGrossSales: LH.isAllow('stores', 'GET::{store}/reports/grossSales') && currentUserModel.stores && currentUserModel.stores.length,
            grossSalesByStores: LH.isAllow('others', 'GET::api/1/reports/grossSalesByStores')
        };

        return _.find(reports ? _.pick(reportsPermissionsMap, reports) : reportsPermissionsMap);
    }
});