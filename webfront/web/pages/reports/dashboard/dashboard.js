define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        StoreGrossSalesByHourModel = require('models/storeGrossSalesByHours'),
        GrossSalesByStores = require('models/grossSalesByStores'),
        currentUserModel = require('models/currentUser');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        models: {
            storeGrossSalesByHour: function(){
                var storeGrossSalesByHourModel = null;

                if (currentUserModel.stores.length && LH.isAllow('stores', 'GET::{store}/reports/grossSales')){
                    storeGrossSalesByHourModel = new StoreGrossSalesByHourModel();
                    storeGrossSalesByHourModel.storeId = currentUserModel.stores.at(0).id
                }

                return storeGrossSalesByHourModel;
            }
        },
        collections: {
            grossSalesByStores: function(){
                var GrossSalesByStores = null;

                if (LH.isAllow('reports', 'GET::grossSalesByStores')){
                    GrossSalesByStores = new GrossSalesByStores();
                }

                return GrossSalesByStores;
            }
        },
        initialize: function(){
            var page = this,
                fetchData = [];

            page.models = {
                storeGrossSalesByHour: page.models.storeGrossSalesByHour(),
                store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
            };

            page.collections = {
                grossSalesByStores: page.collections.grossSalesByStores()
            };

            if (page.models.storeGrossSalesByHour){
                fetchData.push(page.models.storeGrossSalesByHour.fetch());
            }

            if (page.collections.grossSalesByStores){
                fetchData.push(page.collections.grossSalesByStores.fetch());
            }

            $.when.apply($, fetchData).done(function(){
                page.render();
            });
        }
    });
});