define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        globalEvents: {
            'click:receipt': function(){
                this.render();
            }
        },
        models: {
            receipt: function(){
                return PAGE.collections.receipts.get(PAGE.params.receiptId);
            }
        },
        blocks: {
            modal_refund: require('blocks/modal/refund/refund')
        }
    });
});