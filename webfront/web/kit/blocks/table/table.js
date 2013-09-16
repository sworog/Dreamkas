define(function(require) {
        //requirements
        var Block = require('../../core/block.js'),
            DataCollection = require('./collections/data.js'),
            columnsCollection = require('./collections/columns.js');

        return Block.extend({
            __name__: 'table',
            loading: false,
            columns: [],
            collection: [],
            tagName: 'table',
            className: 'table',
            templates: {
                index: require('tpl!kit/blocks/table/templates/index.html'),
                head: require('tpl!kit/blocks/table/templates/head.html'),
                body: require('tpl!kit/blocks/table/templates/body.html'),
                tr: require('tpl!kit/blocks/table/templates/tr.html'),
                td: require('tpl!kit/blocks/table/templates/td.html')
            },
            listeners: {
                collection: {
                    request: function(){
                        var block = this;

                        block.set('loading', true);
                    },
                    sync: function() {
                        var block = this;

                        block.set('loading', false);
                    },
                    change: function() {
                        var block = this;

                        block.renderBody();
                    },
                    reset: function() {
                        var block = this;

                        block.renderBody();
                    },
                    add: function(){
                        var block = this;

                        block.renderBody();
                    },
                    remove: function(){
                        var block = this;

                        block.renderBody();
                    }
                }
            },
            initialize: function(){
                var block = this;

                if (_.isArray(block.columns)){
                    block.columns = new columnsCollection(block.columns);
                }

                if (_.isArray(block.collection)){
                    block.collection = new DataCollection(block.collection);
                }
            },
            renderBody: function(){
                var block = this;

                block.$body.html(block.templates.body(block));
            },
            'set:loading': function(loading){
                var block = this;
                block.$head.toggleClass('preloader_rows', loading);
            },
            findElements: function(){
                var block = this;

                Block.prototype.findElements.apply(block, arguments);

                block.$body = block.$('tbody');
                block.$head = block.$('thead');
            }
        })
    }
);