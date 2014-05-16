// DEPRECATED

define(function(require) {
        //requirements
        var Block = require('kit/core/block'),
            DataCollection = require('./collections/data'),
            columnsCollection = require('./collections/columns');

        return Block.extend({
            __name__: 'table',
            loading: false,
            columns: [],
            collection: [],
            tagName: 'table',
            className: 'table',
            template: require('tpl!blocks/table/templates/index.html'),
            templates: {
                index: require('tpl!blocks/table/templates/index.html'),
                head: require('tpl!blocks/table/templates/head.html'),
                body: require('tpl!blocks/table/templates/body.html'),
                tr: require('tpl!blocks/table/templates/tr.html'),
                td: require('tpl!blocks/table/templates/td.html')
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