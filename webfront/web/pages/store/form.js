define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        StoreModel = require('models/store'),
        Form_store = require('blocks/form/form_store/form_store');

    return Page.extend({
        __name__: 'page_store_form',
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            stores: 'POST'
        },
        initialize: function(){
            var page = this;

            page.storeModel = new StoreModel({
                id: page.storeId,
                subCategory: page.subCategory
            });

            $.when(page.storeId ? page.storeModel.fetch() : {}).then(function(){
                page.render();

                new Form_store({
                    model: page.storeModel,
                    el: document.getElementById('form_store')
                });
            })
        }
    });
});