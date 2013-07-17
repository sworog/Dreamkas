define(function(require) {
    //requirements
    var Page = require('pages/page'),
        StoreModel = require('models/store'),
        Form_store = require('blocks/form/form_store/form_store');

    return Page.extend({
        pageName: 'page_department_form',
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            store: 'POST'
        },
        initialize: function(storeId, departmentId){
            var page = this;

            page.storeModel = new StoreModel({
                id: storeId
            });

            $.when(page.storeModel.fetch()).then(function(){

                page.departmentModel = page.storeModel.departments.get(departmentId);

                page.render();

                new Form_store({
                    model: page.departmentModel,
                    storeModel: page.storeModel,
                    el: document.getElementById('form_department')
                });
            })
        }
    });
});