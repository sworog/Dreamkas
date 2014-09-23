define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        checkKey = require('kit/checkKey/checkKey');

    return Block.extend({
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            if (block.collection) {
                block.listenTo(block.collection, {
                    'change add remove reset': function() {
                        console.log(111);
                        block.render();
                    }
                });
            }

            if (block.itemSelector) {
                block.bindSwitchHandlers();
            }
        },
        bindSwitchHandlers: function() {
            var block = this;

            $(document).on('keyup', function(e) {
                if (checkKey(e.keyCode, ['UP'])) {
                    block.focusItem(block.get('focusedItemIndex') - 1);
                }

                if (checkKey(e.keyCode, ['DOWN'])) {
                    block.focusItem(block.get('focusedItemIndex') + 1);
                }
            });
        },
        focusItem: function(index) {
            var block = this,
                items = block.el.querySelectorAll(block.itemSelector);

            if (!items.length) {
                return;
            }

            if (items[index]) {
                items[index].focus();
            } else if (index < 0) {
                block.focusItem(0);
            } else {
                block.focusItem(items.length - 1);
            }
        },
        focusedItemIndex: function() {
            var block = this;

            return block.$(block.itemSelector).index(document.activeElement);
        }
    });
});