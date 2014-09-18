define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(this, arguments);

            this.listenTo(PAGE, {
                'change:params.receiptId': function() {
                    block.render();
                }
            });
        },
        blocks: {
            modal_refund: require('blocks/modal/refund/refund')
        }
    });
});