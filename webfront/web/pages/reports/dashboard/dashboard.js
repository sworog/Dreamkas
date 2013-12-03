define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        StoreGrossSalesByHourModel = require('models/storeGrossSalesByHours'),
        currentUserModel = require('models/currentUser');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function(){
            return !(LH.isAllow('stores', 'GET::{store}/reports/grossSalesByHours') && currentUserModel.stores.length);
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
        initialize: function(){
            var page = this,
                initData = [];

            page.models = {
                storeGrossSalesByHour: page.models.storeGrossSalesByHour(),
                store: currentUserModel.stores.at(0)
            };

            if (page.models.storeGrossSalesByHour){
                initData.push(page.models.storeGrossSalesByHour.fetch());
            }

            $.when.apply($, initData).done(function(){
                page.render();
            });
        }
    });
});