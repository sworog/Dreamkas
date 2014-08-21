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

        collections: {},
        models: {},

        blocks: {},
        __blocks: {},
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

            block.destroyBlocks();

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
        },

        initBlocks: function() {
            var block = this,
                $blocks = block.$('[block]');

            block.$('button[data-toggle="popover"]').popover({
                trigger: 'focus'
            });

            block.$('.inputDate, .input-daterange').each(function(){
                $(this).datepicker({
                    language: 'ru',
                    format: 'dd.mm.yyyy',
                    autoclose: true,
                    endDate: this.dataset.endDate && this.dataset.endDate.toString(),
                    todayBtn: "linked"
                });
            });

            $blocks.each(function() {
                var placeholder = this,
                    blockName = placeholder.getAttribute('block'),
                    params = _.extend({}, placeholder.dataset, {el: placeholder}),
                    __block;

                if (typeof block.blocks[blockName] === 'function') {

                    __block = block.blocks[blockName].call(block, params);

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

        destroy: function() {
            var block = this;

            block.destroyBlocks();
            block.stopListening();
            block.undelegateEvents();

            block.bindings.unbind();
        },

        remove: function() {
            var block = this;

            block.destroyBlocks();

            return View.prototype.remove.apply(block, arguments);
        },

        destroyBlocks: function() {
            var block = this;

            _.each(block.__blocks, function(blockList, blockName) {

                _.each(blockList, function(blockToRemove) {
                    if (blockToRemove && typeof blockToRemove.destroy === 'function') {
                        blockToRemove.destroy();
                    }
                });

            });

            block.__blocks = {};
        }
    });
});