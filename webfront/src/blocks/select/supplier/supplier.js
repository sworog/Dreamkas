define(function(require, exports, module) {
    //requirements
    var Select = require('blocks/select/select');

    return Select.extend({
        template: require('ejs!./template.ejs'),
        add: false,
        globalEvents: {
            'submit:success': function(data, block) {

                var modal = block.$el.closest('.modal')[0];

                if (modal && modal.id === 'modal_supplier-' + this.cid) {

                    this.selected = data.id;
                    this.render();
                }
            }
        },
        collection: function(){
            return PAGE.collections.suppliers;
        },
        blocks: {
            modal_supplier: require('blocks/modal/supplier/supplier')
        }
    });
});