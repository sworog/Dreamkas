define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        models: {
            store: function(){
                var page = this,
                    StoreModel = require('models/store/store');

                return new StoreModel({
                    id: page.params.storeId
                });
            }
        }
    });
});