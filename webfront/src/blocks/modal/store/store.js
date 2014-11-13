define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        id: 'modal_store',
        storeId: 0,
        template: require('ejs!./template.ejs'),
        blocks: {
            form_store: function(options) {
                var Form_store = require('blocks/form/store/store');

                options.storeId = this.storeId;

                return new Form_store(options);
            }
        }
    });
});