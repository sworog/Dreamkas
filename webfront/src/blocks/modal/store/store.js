define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        id: 'modal_store',
        storeId: 0,
        template: require('ejs!./template.ejs'),
        models: {
            store: function(){
                var StoreModel = require('resources/store/model');

                return PAGE.get('collections.stores').get(this.storeId) || new StoreModel;
            }
        },
        blocks: {
            form_store: function(options) {
                var Form_store = require('blocks/form/store/store');

                options.storeId = this.storeId;

                return new Form_store(options);
            }
        }
    });
});