define(function(require) {
    //requirements
    var $ = require('jquery'),
        Page = require('pages/page'),
        WriteOff = require('blocks/writeOff/writeOff'),
        WriteOffModel = require('models/writeOff');

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

            $.when(page.writeOffModel.fetch()).then(function(){
                page.render();

                new WriteOff({
                    model: page.writeOffModel,
                    writeOffId: page.writeOffId,
                    editMode: page.params.editMode,
                    el: document.getElementById('writeOff')
                });
            });
        }
    });
});