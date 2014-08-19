define(function(require, exports, module) {
    //requirements
    var Backbone = require('backbone'),
        get = require('kit/get/get'),
        set = require('kit/set/set'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        makeClass = require('kit/makeClass/makeClass'),
        _ = require('lodash');

    var View = Backbone.View;

    return makeClass(View, {

        constructor: function(params) {
            var block = this;

            deepExtend(block, params);

            View.apply(block, arguments);
        },

        blocks: {},
        __blocks: {},

        template: function() {
            return '<div></div>';
        },

        initialize: function(){
            var block = this;

            block.render();
        },

        render: function() {
            var block = this,
                $newElement = $(block.template(block));

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
            var block = this,
                $blocks = block.$(_.keys(block.blocks).join(', ')),
                blocksTagMap;

            _.each(block.blocks, function(blockConstructor, blockName){
                blocksTagMap[blockName.toUpperCase()] = blockName;
            });

            $blocks.each(function(){
                var el = this,
                    $el = $(el),
                    blockName = blocksTagMap[el.tagName],
                    __block = block.blocks[blockName].call(block, _.clone(el.dataset));

                $el.replaceWith(__block.el);

                block.__blocks[blockName] = block.__blocks[blockName] || [];

                block.__blocks[blockName].push(__block);
            });
        },

        remove: function() {
            var block = this;

            block.removeBlocks();

            return View.prototype.remove.apply(block, arguments);
        },

        removeBlocks: function() {
            var block = this;

            _.each(block.__blocks, function(blockList, blockName) {

                _.each(blockList, function(blockToRemove){
                    if (blockToRemove && typeof blockToRemove.remove === 'function') {
                        blockToRemove.remove();
                    }
                });

            });

            block.__blocks = {};
        }
    });
});