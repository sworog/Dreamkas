define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        collection: function() {
            return PAGE.collections.stockMovements;
        },
        collections: {
            stores: function() {
                return PAGE.collections.stores;
            }
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate'),
            select_store: require('blocks/select/store/store')
        }
    });
});