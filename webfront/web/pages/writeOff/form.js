define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Form_writeOff = require('blocks/form/form_writeOff/form_writeOff'),
        WriteOffModel = require('models/writeOff'),
        currentUserModel = require('models/currentUser'),
        InputDate_noTime = require('blocks/inputDate/inputDate_noTime'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_writeOff_form',
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        initialize: function(pageParams) {
            var page = this;

            if (!LH.isAllow('stores/{store}/writeoffs', 'POST')){
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
                storeId: pageParams.storeId
            });

            page.render();

            new Form_writeOff({
                model: page.writeOffModel,
                el: document.getElementById('form_writeOff')
            });

            new InputDate_noTime({
                el: page.el.querySelector('[name="date"]')
            });

        }
    });
});