define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        globalEvents = require('kit/globalEvents/globalEvents'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        model: null,
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(this, arguments);

            this.listenTo(globalEvents, {
                'click:receipt': function(receiptModel) {
                    block.render({
                        model: receiptModel
                    });
                }
            });
        },
        blocks: {
            modal_refund: require('blocks/modal/refund/refund')
        }
    });
});