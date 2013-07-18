define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Department = require('blocks/department/department'),
        StoreModel = require('models/store');

    return Page.extend({
        pageName: 'page_department_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        permissions: {
            departments: 'GET::{department}'
        },
        initialize: function(storeId, departmentId) {
            var page = this;

            page.departmentId = departmentId;

            page.storeModel = new StoreModel({
                id: storeId
            });

            $.when(page.storeModel.fetch()).then(function(){

                page.departmentModel = page.storeModel.departments.get(departmentId);

                page.render();

                new Department({
                    storeModel: page.storeModel,
                    departmentModel: page.departmentModel,
                    el: document.getElementById('department')
                });
            });
        }
    });
});