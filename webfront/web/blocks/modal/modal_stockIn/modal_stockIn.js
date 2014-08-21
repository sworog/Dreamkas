define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./modal_stockIn.ejs'),
        models: {
            stockIn: require('models/stockIn/stockIn')
        },
        collections: {
            stores: function(){
                return PAGE.collections.stores;
            }
        }
    });
});