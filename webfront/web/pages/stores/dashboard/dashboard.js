define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        models: {
            storeGrossSales: function(){
                var page = this,
                    StoreGrossSalesModel = require('models/storeGrossSales'),
                    storeGrossSalesModel;

                storeGrossSalesModel = new StoreGrossSalesModel();
                storeGrossSalesModel.storeId = page.get('params.storeId');

                return storeGrossSalesModel;
            }
        }
    });
});