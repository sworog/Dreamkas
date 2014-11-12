define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        modalId: null,
        selected: null,
        all: false,
        add: false,
        globalEvents: {
            'submit:success': function(data, block) {

                var modal = block.$el.closest('.modal')[0];

                if (modal && modal.id === 'modal_store' + this.cid) {

                    this.selected = data.id;
                    this.render();
                }
            }
        },
        collection: function(){
            return PAGE.collections.stores;
        },
        blocks: {
            modal_store: require('blocks/modal/store/store')
        }
    });
});