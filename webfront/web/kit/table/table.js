define(function(require) {
        //requirements
        var Block = require('kit/block'),
            _ = require('underscore'),
            DataCollection = require('kit/table/collections/data'),
            columnsCollection = require('kit/table/collections/columns');

        return Block.extend({
            loading: false,
            columns: [],
            data: [],
            tagName: 'table',
            className: 'table',
            blockName: 'table',
            templates: {
                index: require('tpl!./templates/index.html'),
                head: require('tpl!./templates/head.html'),
                body: require('tpl!./templates/body.html'),
                tr: require('tpl!./templates/tr.html'),
                td: require('tpl!./templates/td.html')
            },
            listeners: {
                data: {
                    reset: function() {
                        var block = this;

                        block.renderBody();
                        block.set('loading', false);
                    },
                    request: function() {
                        var block = this;

                        block.set('loading', true);
                    }
                }
            },
            initialize: function(){
                var block = this;

                if (_.isArray(block.data)){
                    block.data = new DataCollection(block.data);
                }

                if (_.isArray(block.columns)){
                    block.columns = new columnsCollection(block.columns);
                }

                Block.prototype.initialize.call(block);

                block.listenTo(block.collection, {
                    reset: function() {
                        block.renderBody();
                        block.set('loading', false);
                    },
                    request: function() {
                        block.set('loading', true);
                    }
                });
            },
            renderBody: function(){
                var block = this;

                block.$body.html(block.templates.body({block: block}));
            },
            'set:loading': function(loading){
                var block = this;
                block.find('thead').toggleClass('preloader_rows', loading);
            }
        })
    }
);