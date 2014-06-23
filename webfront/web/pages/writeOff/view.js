define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        WriteOff = require('blocks/writeOff/writeOff'),
        WriteOffModel = require('models/writeOff'),
        WriteOffProductsCollection = require('collections/writeOffProducts'),
        currentUserModel = require('models/currentUser.inst'),
        Page403 = require('pages/errors/403'),
        Page404 = require('pages/errors/404');

    return Page.extend({
        __name__: 'page_writeOff_view',
        params: {
            writeOffId: null,
            editMode: null
        },
        partials: {
            '#content': require('ejs!./templates/view.html')
        },
        initialize: function(pageParams) {
            var page = this;

            if (!LH.isAllow('stores/{store}/writeoffs/{writeOff}/products', 'POST')){
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

            page.writeOffId = pageParams.writeOffId;

            page.writeOffModel = new WriteOffModel({
                id: page.params.writeOffId
            });

            page.writeOffProductsCollection = new WriteOffProductsCollection({
                writeOffId: page.params.writeOffId,
                storeId: pageParams.storeId
            });

            $.when(page.writeOffModel.fetch(), page.writeOffProductsCollection.fetch()).then(function(){
                page.render();

                new WriteOff({
                    writeOffModel: page.writeOffModel,
                    writeOffProductsCollection: page.writeOffProductsCollection,
                    editMode: page.params.editMode,
                    el: document.getElementById('writeOff')
                });
            }, function() {
                new Page404();
            });
        }
    });
});