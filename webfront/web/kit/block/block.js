define(function(require, exports, module) {
    //requirements
    var Backbone = require('backbone'),
        get = require('kit/get/get'),
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

            block.destroyBlocks();

            block.setElement($newElement.replaceAll(block.el));

            block.initBlocks();
        },

        get: function() {
            var args = [this].concat([].slice.call(arguments));

            return get.apply(null, args);
        },

        initBlocks: function() {
            var block = this,
                $blocks = block.$(_.keys(block.blocks).join(', ')),
                blocksTagMap = {};

            _.each(block.blocks, function(blockConstructor, blockName){
                blocksTagMap[blockName.toUpperCase()] = blockName;
            });

            $blocks.each(function(){
                var placeholder = this,
                    $placeholder = $(placeholder),
                    blockName = blocksTagMap[placeholder.tagName],
                    __block = block.blocks[blockName].call(block, _.clone(placeholder.dataset));

                $placeholder.replaceWith(__block.el);

                block.__blocks[blockName] = block.__blocks[blockName] || [];

                block.__blocks[blockName].push(__block);
            });
        },

        destroy: function(){
            var block = this;

            block.destroyBlocks();

            block.stopListening();
            block.undelegateEvents();
        },

        remove: function() {
            var block = this;

            block.destroyBlocks();

            return View.prototype.remove.apply(block, arguments);
        },

        destroyBlocks: function() {
            var block = this;

            _.each(block.__blocks, function(blockList, blockName) {

                _.each(blockList, function(blockToRemove){
                    if (blockToRemove && typeof blockToRemove.destroy === 'function') {
                        blockToRemove.destroy();
                    }
                });

            });

            block.__blocks = {};
        }
    });
});