define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./form_stockIn.ejs'),
        model: require('resources/stockIn/model'),
        collection: function(){
            return PAGE.collections.stockMovements;
        },
        collections: {
            stores: function(){
                return PAGE.collections.stores;
            }
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate')
        }
    });
});