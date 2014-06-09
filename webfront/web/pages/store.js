define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        params: {
            storeId: null
        },
        partials: {
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation_store.ejs')
        },
        models: {
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