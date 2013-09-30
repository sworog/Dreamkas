define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Table_writeOffs = require('blocks/table/table_writeOffs/table_writeOffs'),
        WriteOffsCollection = require('collections/writeOffs'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_writeOffs_list',
        partials: {
            '#content': require('tpl!./templates/list.html')
        },
        initialize: function(pageParams){
            var page = this;

            if (currentUserModel.stores.length){
                pageParams.storeId = currentUserModel.stores.at(0).id;
            }

            if (!pageParams.storeId){
                new Page403();
                return;
            }

            page.writeOffsCollection = new WriteOffsCollection([], {
                storeId: pageParams.storeId
            });

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