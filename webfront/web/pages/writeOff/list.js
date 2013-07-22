define(function(require) {
    //requirements
    var $ = require('jquery'),
        Page = require('pages/page'),
        Table_writeOffs = require('blocks/table/table_writeOffs/table_writeOffs'),
        WriteOffsCollection = require('collections/writeOffs');

    return Page.extend({
        pageName: 'page_writeOffs_list',
        templates: {
            '#content': require('tpl!./templates/list.html')
        },
        permissions: {
            writeoffs: 'GET'
        },
        initialize: function(){
            var page = this;

            page.writeOffsCollection = new WriteOffsCollection();

            $.when(page.writeOffsCollection.fetch()).then(function(){
                page.render();
                new Table_writeOffs({
                    collection: page.writeOffsCollection,
                    el: document.getElementById('table_writeOffs')
                });
            });
        }
    });
});