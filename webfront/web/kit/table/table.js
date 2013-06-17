define(function(require) {
        //requirements
        var Block = require('kit/block'),
            _ = require('underscore'),
            DataCollection = require('kit/table/collections/data'),
            columnsCollection = require('kit/table/collections/columns');

        return Block.extend({
            loading: false,
            columns: [],
            Collection: DataCollection,
            collection: [],
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
                collection: {
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
            $body: null,
            $head: null,
            initialize: function(){
                var block = this;

                if (_.isArray(block.columns)){
                    block.columns = new columnsCollection(block.columns);
                }

                Block.prototype.initialize.call(block);
            },
            fetch: function(){
                var block = this;

                if (block.collection.url){
                    block.collection.fetch();
                }
            },
            renderBody: function(){
                var block = this;

                block.$body.html(block.templates.body({block: block}));
            },
            'set:loading': function(loading){
                var block = this;
                block.$head.toggleClass('preloader_rows', loading);
            }
        })
    }
);