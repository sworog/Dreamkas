define(function(require, exports, module) {
    //requirements
    var Backbone = require('backbone'),
        get = require('kit/get/get'),
        set = require('kit/set/set'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        makeClass = require('kit/makeClass/makeClass'),
        rivets = require('bower_components/rivets/dist/rivets'),
        _ = require('lodash');

    require('sortable');

    var View = Backbone.View;

    return makeClass(View, {

        constructor: function(params) {
            var block = this;

            deepExtend(block, params);

            View.apply(block, arguments);
        },

        bindings: null,

        initialize: function() {
            var block = this;

            block.initResources();
            block.render();
        },

        helpers: {
            formatMoney: require('kit/formatMoney/formatMoney'),
            formatAmount: require('kit/formatAmount/formatAmount'),
            formatDate: require('kit/formatDate/formatDate')
        },

        render: function() {
            var block = this;

            block.removeBlocks();

            if (typeof block.template === 'function') {
                block.setElement($(block.template(block)).replaceAll(block.el));
            }

            block.bindings = rivets.bind(block.el, block);
            block.el.block = this;

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

        initResources: function(){
            var block = this;

            block.collections = _.transform(block.collections, function(result, collectionInitializer, key) {
                result[key] = block.get('collections.' + key);
            });

            block.models = _.transform(block.models, function(result, modelInitializer, key) {
                result[key] = block.get('models.' + key);
            });

            block.collection = block.get('collection');
            block.model = block.get('model');

        },

        initBlocks: function() {
            var block = this,
                $blocks = block.$('[block]');

            block.__blocks = {};

            $blocks.each(function() {
                var placeholder = this,
                    blockName = placeholder.getAttribute('block'),
                    params = _.extend({}, placeholder.dataset, {el: placeholder}),
                    __block = block.get('blocks.' + blockName, [params]);

                if (__block && __block.el) {

                    __block.el.removeAttribute('block');

                    block.__blocks[blockName] = block.__blocks[blockName] || [];

                    block.__blocks[blockName].push(__block);
                }
            });
        },

        fetch: function() {
            var block = this;

            var dataList =  _.values(block.collections).concat(_.filter(block.models, function(model) {
                return model && model.id;
            }));

            var fetchList = _.map(dataList, function(data) {
                return (data && typeof data.fetch === 'function') ? data.fetch() : data;
            });

            return $.when.apply($, fetchList);
        },

        remove: function() {
            var block = this;

            block.removeBlocks();

            block.bindings.unbind();

            return View.prototype.remove.apply(block, arguments);
        },

        removeBlocks: function() {
            var block = this;

            _.each(block.__blocks, function(blockList, blockName) {

                _.each(blockList, function(blockToRemove) {
                    if (blockToRemove && typeof blockToRemove.remove === 'function') {
                        blockToRemove.remove();
                    }
                });

            });

            block.__blocks = {};
        }
    });
});