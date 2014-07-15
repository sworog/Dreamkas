define(function(require, exports, module) {
    //requirements
    var Backbone = require('backbone'),
        get = require('kit/get/get'),
        set = require('kit/set/set'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        _ = require('lodash');

    var View = Backbone.View;

    return View.extend({

        constructor: function(params){
            var block = this;

            deepExtend(block, params);

            View.apply(block, arguments);
        },

        blocks: {},

        template: function() {
            return '<div></div>';
        },

        el: function(){
            var block = this;

            return block.template(block);
        },

        render: function() {
            var block = this,
                $newEl = $(block.template(block));

            block.removeBlocks();

            block.el.innerHTML = $newEl.html();

            _.forEach($newEl[0].attributes, function(attribute) {
                block.el.setAttribute(attribute.name, attribute.value);
            });

            block._initBlocks();
        },

        get: function(){
            var args = [this].concat([].slice.call(arguments));

            return get.apply(null, args);
        },

        set: function(){
            var args = [this].concat([].slice.call(arguments));

            return set.apply(null, args);
        },

        _initBlocks: function() {
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

        removeBlocks: function(){
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