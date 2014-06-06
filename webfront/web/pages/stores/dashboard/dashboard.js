define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            globalNavigation: require('rv!blocks/globalNavigation/globalNavigation_store.html')
        },
        resources: {
            storeGrossSales: function(){
                var page = this,
                    StoreGrossSalesModel = require('models/storeGrossSales'),
                    storeGrossSalesModel;

                storeGrossSalesModel = new StoreGrossSalesModel();
                storeGrossSalesModel.storeId = page.get('params.storeId');

                return storeGrossSalesModel;
            },
            store: function(){
                var page = this,
                    StoreModel = require('models/store');

                return new StoreModel({
                    id: page.get('params.storeId')
                });
            }
        }
    });
});