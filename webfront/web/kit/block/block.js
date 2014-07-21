define(function(require, exports, module) {
    //requirements
    var Backbone = require('backbone'),
        get = require('kit/get/get'),
        set = require('kit/set/set'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        makeClass = require('kit/makeClass/makeClass'),
        rivets = require('bower_components/rivets/dist/rivets'),
        _ = require('lodash');

    var View = Backbone.View;

    return makeClass(View, {

        constructor: function(params) {
            var block = this;

            deepExtend(block, params);

            View.apply(block, arguments);
        },

        blocks: {},

        template: function() {
            return '<div></div>';
        },

        el: function() {
            var block = this;

            return block.template(block);
        },

        render: function() {
            var block = this,
                $newElement = $(block.template(block));

            block.__rivets && block.__rivets.unbind();

            block.__rivets = rivets.bind($newElement, block);

            block.removeBlocks();

            block.setElement($newElement.replaceAll(block.el));

            block.initBlocks();
        },

        get: function() {
            var args = [this].concat([].slice.call(arguments));

            return get.apply(null, args);
        },

        set: function() {
            var args = [this].concat([].slice.call(arguments));

            return set.apply(null, args);
        },

        initBlocks: function() {
            var block = this;

            block.__blocks = block.__blocks || block.blocks;

            block.blocks = _.transform(block.__blocks, function(result, blockInitializer, key) {
                result[key] = block.get('__blocks.' + key);
            });
        },

        remove: function() {
            var block = this;

            block.removeBlocks();

            return View.prototype.remove.apply(block, arguments);
        },

        removeBlocks: function() {
            var block = this;

            _.forEach(block.blocks, function(blockToRemove, blockName) {

                if (blockToRemove && typeof blockToRemove.remove === 'function') {
                    blockToRemove.remove();
                    delete block.blocks[blockName];
                }

            });
        }
    });
});