define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            globalNavigation: require('rv!blocks/globalNavigation/globalNavigation_store.html'),
        },
        resources: {
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