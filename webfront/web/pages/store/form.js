define(function(require) {
    //requirements
    var Page = require('pages/page'),
        StoreModel = require('models/store'),
        Form_store = require('blocks/form/form_store/form_store');

    return Page.extend({
        pageName: 'page_store_form',
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            products: 'POST'
        },
        initialize: function(storeId, params){
            var page = this;

            if (storeId && typeof storeId !== 'string'){
                params = storeId;
                storeId = null;
            }

            params = params || {};

            page.storeId = storeId;

            page.storeModel = new StoreModel({
                id: page.storeId,
                subCategory: params.subCategory
            });

            $.when(storeId ? page.storeModel.fetch() : {}).then(function(){
                page.render();

                new Form_store({
                    model: page.storeModel,
                    el: document.getElementById('form_store')
                });
            })
        }
    });
});