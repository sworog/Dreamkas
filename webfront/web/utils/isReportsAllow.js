define(function(require, exports, module) {
    //requirements
    var currentUserModel = require('models/currentUser');

    require('lodash');

    return function(reports){
        var reportsPermissionsMap = {
            storeGrossSalesByHours: LH.isAllow('stores', 'GET::{store}/reports/grossSalesByHours') && currentUserModel.stores && currentUserModel.stores.length,
            storeGrossSales: LH.isAllow('stores', 'GET::{store}/reports/grossSales') && currentUserModel.stores && currentUserModel.stores.length,
            grossSalesByStores: LH.isAllow('others', 'GET::api/1/reports/grossSalesByStores'),
            grossSales: LH.isAllow('others', 'GET::api/1/reports/grossSales'),
            grossSalesByGroups: LH.isAllow('stores', 'GET::{store}/reports/grossSalesByGroups') && currentUserModel.stores && currentUserModel.stores.length,
            grossSalesByCategories: LH.isAllow('stores/{store}/groups', 'GET::{group}/reports/grossSalesByCategories') && currentUserModel.stores && currentUserModel.stores.length,
            grossSalesBySubCategories: LH.isAllow('stores/{store}/categories/{category}', 'GET::reports/grossSalesBySubCategories') && currentUserModel.stores && currentUserModel.stores.length,
            grossSalesByProducts: LH.isAllow('stores/{store}/subcategories/{subCategory}', 'GET::reports/grossSalesByProducts') && currentUserModel.stores && currentUserModel.stores.length
        };

        return _.find(reports ? _.pick(reportsPermissionsMap, reports) : reportsPermissionsMap);
    }
});