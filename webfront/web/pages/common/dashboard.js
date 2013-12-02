define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        StoreGrossSalesModel = require('models/storeGrossSales'),
        currentUserModel = require('models/currentUser');

    require('jquery');

    return Page.extend({
        __name__: 'page_common_dashboard',
        partials: {
            '#content': require('tpl!./templates/dashboard.html')
        },
        models: {
            storeGrossSales: function(){
                var storeGrossSalesModel = null;

                if (currentUserModel.stores.length && LH.isAllow('stores', 'GET::{store}/reports/grossSales')){
                    storeGrossSalesModel = new StoreGrossSalesModel();
                    storeGrossSalesModel.storeId = currentUserModel.stores.at(0).id
                }

                return storeGrossSalesModel;
            }
        },
        initialize: function(){
            var page = this,
                initData = [];

            page.models = {
                storeGrossSales: page.models.storeGrossSales()
            };

            if (page.models.storeGrossSales){
                initData.push(page.models.storeGrossSales.fetch());
            }

            $.when.apply($, initData).done(function(){
                page.render();
            });
        }
    });
});