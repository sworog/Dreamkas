define(function(require, exports, module) {
    //requirements
    var currentUserModel = require('models/currentUser');

    require('lodash');

    return function(reports){

        var hasStores = currentUserModel.stores && currentUserModel.stores.length;

        var reportsPermissionsMap = {
            storeGrossSalesByHours: LH.isAllow('stores', 'GET::{store}/reports/grossSalesByHours') && hasStores,
            storeGrossSales: LH.isAllow('stores', 'GET::{store}/reports/grossSales') && hasStores,
            grossSalesByStores: LH.isAllow('others', 'GET::api/1/reports/grossSalesByStores'),
            grossSales: LH.isAllow('others', 'GET::api/1/reports/grossSales'),
            grossSalesByGroups: LH.isAllow('stores', 'GET::{store}/reports/grossSalesByGroups') && hasStores,
            grossMargin: LH.isAllow('stores', 'GET::{store}/reports/grossSalesByGroups') && hasStores,
            grossSalesByCategories: LH.isAllow('stores/{store}/groups', 'GET::{group}/reports/grossSalesByCategories') && hasStores,
            grossSalesBySubCategories: LH.isAllow('stores/{store}/categories/{category}', 'GET::reports/grossSalesBySubCategories') && hasStores,
            grossSalesByProducts: LH.isAllow('stores/{store}/subcategories/{subCategory}', 'GET::reports/grossSalesByProducts') && hasStores
        };

        var filteredReports = reports ? _.pick(reportsPermissionsMap, reports) : reportsPermissionsMap;

        var allowedReports = reports ? _.every(filteredReports) : [];

        if (_.isArray(allowedReports)){
            _.forEach(filteredReports, function(value, key){
                if (value){
                    allowedReports.push(key);
                }
            });

            if (!allowedReports.length){
                allowedReports = false;
            }
        }

        return allowedReports;
    }
});