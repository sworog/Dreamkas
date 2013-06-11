define(function(require) {
        return Backbone.Block.extend({
            loading: false,
            columns: [],
            data: [],
            tagName: 'table',
            className: 'table',
            templates: {
                index: require('tpl!./templates/index.html'),
                tr: require('tpl!./templates/tr.html'),
                td: require('tpl!./templates/td.html')
            },

            'set:loading': function(loading){
                var block = this;
                block.find('thead').toggleClass('preloader_rows', loading);
            }
        })
    }
);