define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        id: 'modal_store',
        storeId: 0,
        template: require('ejs!./template.ejs'),
        blocks: {
            form_store: function(){
                var block = this,
                    Form_store = require('blocks/form/store/store');

                return new Form_store({
                    storeId: block.storeId
                });
            }
        }
    });
});