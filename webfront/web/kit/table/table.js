define(function(require) {
        //requirements
        var Block = require('kit/block'),
            Backbone = require('backbone');

        return Block.extend({
            loading: false,
            columns: [],
            data: [],
            collection: null,
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
            initialize: function(){
                var block = this;

                block.collection = block.collection || new Backbone.Collection(block.data);

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