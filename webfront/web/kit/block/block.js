define(function(require, exports, module) {
    //requirements
    var Backbone = require('backbone'),
        get = require('kit/get/get'),
        set = require('kit/set/set'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        makeClass = require('kit/makeClass/makeClass'),
        rivets = require('kit/rivets/rivets'),
        globalEvents = require('kit/globalEvents/globalEvents'),
        _ = require('lodash');

    var View = Backbone.View;

    // Cached regex to split keys for `delegate`.
    var delegateEventSplitter = /^(\S+)\s*(.*)$/;

    return makeClass(View, {

        constructor: function(params) {

            deepExtend(this, params);

            this.delegateGlobalEvents();

            View.apply(this, arguments);

            this.render();

        },

        bindings: null,

        formatMoney: require('kit/formatMoney/formatMoney'),
        formatAmount: require('kit/formatAmount/formatAmount'),
        formatDate: require('kit/formatDate/formatDate'),
        formatNumber: require('kit/formatNumber/formatNumber'),
        formatTime: require('kit/formatTime/formatTime'),
        formatDateTime: require('kit/formatDateTime/formatDateTime'),
        normalizeNumber: require('kit/normalizeNumber/normalizeNumber'),

        initialize: function(){

            var block = this;

            //save data constructors in hidden fields

            block.__collections = block.__collections || block.collections;
            block.__models = block.__models || block.models;

            block.__collection = block.__collection || block.collection;
            block.__model = block.__model || block.model;

        },

        render: function(data) {
            var block = this;

            //always get actual data before rendering

            return $.when(block.initData(data)).then(function(){

                if (typeof block.template !== 'function') {
                    return;
                }

                block.removeBlocks();

                block.bindings && block.bindings.unbind();

                block.setElement($(block.template(block)).replaceAll(block.el));

                block.bindings = rivets.bind(block.el, block);

                block.initBlocks();

                block.el.block = block;

            });

        },

        get: function() {
            var args = [this].concat([].slice.call(arguments));

            return get.apply(null, args);
        },

        set: function() {
            var args = [this].concat([].slice.call(arguments));

            return set.apply(null, args);
        },

        initData: function(data) {
            var block = this;

            if (data){

                block.set(data);

                _.extend(block.__collections, data.collections);
                _.extend(block.__models, data.models);

                block.__collection = data.collection || block.__collection;
                block.__model = data.model || block.__model;
            }

            //get data from initial constructors

            block.collections = _.transform(block.__collections, function(result, collectionInitializer, key) {
                result[key] = block.get('__collections.' + key);
            });

            block.models = _.transform(block.__models, function(result, modelInitializer, key) {
                result[key] = block.get('__models.' + key);
            });

            block.collection = block.get('__collection');
            block.model = block.get('__model');

        },

        initBlocks: function() {
            var block = this,
                $blocks = block.$('[block]');

            block.__blocks = {};

            $blocks.each(function() {
                var placeholder = this,
                    blockName = placeholder.getAttribute('block'),
                    params = _.extend({}, placeholder.dataset, {el: placeholder});

                var __block = block.get('blocks.' + blockName, [params]);

                if (__block && __block.el) {

                    __block.el.removeAttribute('block');

                    block.__blocks[blockName] = block.__blocks[blockName] || [];

                    block.__blocks[blockName].push(__block);

                    if (!block.$(__block.el).length && block.$(placeholder).length) {
                        __block.$el.replaceAll(placeholder);
                    }
                }
            });
        },

        fetch: function() {
            var block = this;

            var dataList = _.values(block.collections).concat(_.filter(block.models, function(model) {
                return model && model.id;
            }));

            var fetchList = _.map(dataList, function(data) {
                return (data && typeof data.fetch === 'function') ? data.fetch() : data;
            });

            return $.when.apply($, fetchList);
        },

        unbind: function() {
            var block = this;

            block.stopListening();
            block.undelegateEvents();
            block.bindings && block.bindings.unbind();
        },

        remove: function() {
            var block = this;

            block.unbind();
            block.removeBlocks();

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
        },

        trigger: function(event, data) {
            var block = this;

            View.prototype.trigger.apply(block, arguments);

            globalEvents.trigger(event, data, block);
        },

        delegateGlobalEvents: function() {

            for (var key in this.globalEvents) {
                var method = this.globalEvents[key];

                var match = key.match(delegateEventSplitter);

                var eventName = match[1], selector = match[2];

                method = _.bind(method, this);

                this.listenTo(globalEvents, eventName, method);
            }

            return this;
        }
    });
});