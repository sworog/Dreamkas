define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        WriteOff = require('blocks/writeOff/writeOff'),
        WriteOffModel = require('models/writeOff'),
        WriteOffProductsCollection = require('collections/writeOffProducts'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_writeOff_view',
        partials: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function(pageParams) {
            var page = this;

            if (!LH.isAllow('stores/{store}/writeoffs/{writeoff}/products', 'POST')){
                new Page403();
                return;
            }

            if (currentUserModel.stores.length){
                pageParams.storeId = currentUserModel.stores.at(0).id;
            }

            if (!pageParams.storeId){
                new Page403();
                return;
            }

            page.writeOffModel = new WriteOffModel({
                id: page.writeOffId
            });

            page.writeOffProductsCollection = new WriteOffProductsCollection({
                writeOffId: page.writeOffId,
                storeId: pageParams.storeId
            });

            $.when(page.writeOffModel.fetch(), page.writeOffProductsCollection.fetch()).then(function(){
                page.render();

                new WriteOff({
                    writeOffModel: page.writeOffModel,
                    writeOffProductsCollection: page.writeOffProductsCollection,
                    editMode: page.editMode,
                    el: document.getElementById('writeOff')
                });
            });
        }
    });
});