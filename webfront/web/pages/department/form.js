define(function(require) {
    //requirements
    var Page = require('kit/page'),
        StoreModel = require('models/store'),
        DepartmentModel = require('models/department'),
        Form_department = require('blocks/form/form_department/form_department');

    return Page.extend({
        __name__: 'page_department_form',
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            departments: 'POST'
        },
        initialize: function(){
            var page = this;

            page.storeModel = new StoreModel({
                id: page.storeId
            });

            $.when(page.storeModel.fetch()).then(function(){

                page.departmentModel = page.storeModel.departments.get(page.departmentId) || new DepartmentModel({
                    store: page.storeId
                });

                page.render();

                new Form_department({
                    model: page.departmentModel,
                    storeModel: page.storeModel,
                    el: document.getElementById('form_department')
                });
            })
        }
    });
});