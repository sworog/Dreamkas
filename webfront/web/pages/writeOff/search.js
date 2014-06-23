define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        WriteOffsCollection = require('collections/writeOffs'),
        Form_writeOffSearch = require('blocks/form/form_writeOffSearch/form_writeOffSearch'),
        currentUserModel = require('models/currentUser.inst'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_invoice_list',
        partials: {
            '#content': require('ejs!./templates/search.html')
        },
        initialize: function(pageParams) {
            var page = this;

            if (currentUserModel.stores.length) {
                pageParams.storeId = currentUserModel.stores.at(0).id;
            }

            if (!pageParams.storeId) {
                new Page403();
                return;
            }

            page.writeOffsCollection = new WriteOffsCollection([], {
                storeId: pageParams.storeId
            });

            page.render();

            new Form_writeOffSearch({
                el: document.getElementsByClassName('form_writeOffSearch'),
                writeOffsCollection: page.writeOffsCollection
            });
        }
    });
});