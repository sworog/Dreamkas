define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        StoreGrossSalesModel = require('models/storeGrossSales'),
        GrossSalesModel = require('models/grossSales'),
        currentUserModel = require('models/currentUser.inst');

    require('jquery');

    return Page.extend({
        __name__: 'page_common_dashboard',
        partials: {
            '#content': require('tpl!./templates/dashboard.html')
        },
        models: {
            storeGrossSales: function(){
                var storeGrossSalesModel = null;

                if (LH.isReportsAllow(['storeGrossSales'])){
                    storeGrossSalesModel = new StoreGrossSalesModel();
                    storeGrossSalesModel.storeId = currentUserModel.stores.at(0).id
                }

                return storeGrossSalesModel;
            },
            grossSales: function(){
                var grossSalesModel = null;

                if (LH.isReportsAllow(['grossSales'])){
                    grossSalesModel = new GrossSalesModel();
                }

                return grossSalesModel;
            }
        },
        initialize: function(){
            var page = this,
                initData = [];

            page.models = {
                storeGrossSales: page.models.storeGrossSales(),
                grossSales: page.models.grossSales()
            };

            if (page.models.storeGrossSales){
                initData.push(page.models.storeGrossSales.fetch());
            }

            if (page.models.grossSales){
                initData.push(page.models.grossSales.fetch());
            }

            $.when.apply($, initData).done(function(){
                page.render();
            });
        }
    });
});