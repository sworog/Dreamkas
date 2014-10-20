define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        Collection = require('kit/collection/collection'),
        checkKey = require('kit/checkKey/checkKey');

    return Block.extend({
        sortedCollection: null,
        sortBy: '',
        sortDirection: 'ascending',
        events: {
            'click [data-sort-by]': function(e){
                var block = this,
                    sortBy = e.currentTarget.dataset.sortBy,
                    sortDirection = e.currentTarget.dataset.sortedDirection || 'descending';

                if (sortDirection === 'descending'){
                    sortDirection = 'ascending';
                } else {
                    sortDirection = 'descending';
                }

                block.sort({
                    sortBy: sortBy,
                    sortDirection: sortDirection
                });
            }
        },
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            if (block.itemSelector) {
                block.bindSwitchHandlers();
            }
        },
        render: function() {
            var block = this;

            Block.prototype.render.apply(block, arguments);

            var $sortTriggers = block.$('[data-sort-by]'),
                $sortTrigger = block.$('[data-sort-by="' + block.sortBy + '"]');

            $sortTriggers.removeAttr('data-sorted-direction');

            $sortTrigger.attr('data-sorted-direction', block.sortDirection);
        },
        initData: function(){

            var block = this,
                sortedList;

            Block.prototype.initData.apply(this, arguments);

            if (block.collection) {

                sortedList = block.collection.sortBy(block.get('sortBy'));

                if (block.get('sortDirection') === 'descending') {
                    sortedList.reverse();
                }

                block.sortedCollection = new Collection(sortedList);

                block.listenTo(block.collection, {
                    'change add remove reset': function() {
                        block.render();
                    }
                });
            }
        },
        sort: function(opt) {

            var block = this;

            block.set(opt);

            block.render();
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