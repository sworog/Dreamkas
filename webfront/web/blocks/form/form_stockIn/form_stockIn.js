define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: require('models/stockIn/stockIn'),
        collection: function(){
            return PAGE.collections.stockMovements;
        },
        collections: {
            stores: function(){
                return PAGE.collections.stores;
            }
        },
        partials: {
            select_stores: require('ejs!blocks/select/select_stores/select_stores.ejs')
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate')
        }
    });
});