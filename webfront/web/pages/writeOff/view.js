define(function(require) {
    //requirements
    var $ = require('jquery'),
        Page = require('pages/page'),
        WriteOff = require('blocks/writeOff/writeOff'),
        WriteOffModel = require('models/writeOff'),
        WriteOffProductsCollection = require('collections/writeOffProducts');

    return Page.extend({
        pageName: 'page_writeOff_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        permissions: {
            writeoffs: 'GET::{writeOff}'
        },
        initialize: function(writeOffId, params) {
            var page = this;

            page.writeOffId = writeOffId;
            page.params = params || {};

            page.writeOffModel = new WriteOffModel({
                id: page.writeOffId
            });

            page.writeOffProductsCollection = new WriteOffProductsCollection({
                writeOffId: page.writeOffId
            });

            $.when(page.writeOffModel.fetch(), page.writeOffProductsCollection.fetch()).then(function(){
                page.render();

                new WriteOff({
                    writeOffModel: page.writeOffModel,
                    writeOffProductsCollection: page.writeOffProductsCollection,
                    editMode: page.params.editMode,
                    el: document.getElementById('writeOff')
                });
            });
        }
    });
});