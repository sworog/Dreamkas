define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Department = require('blocks/department/department'),
        getUserStore = require('utils/getUserStore'),
        StoreModel = require('models/store'),
        Page403 = require('pages/403/403');

    return Page.extend({
        pageName: 'page_department_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function() {
            var page = this,
                userStoreModel = getUserStore(page.storeId);

            if (!(LH.isAllow('departments', 'GET::{department}') || userStoreModel)){
                new Page403();
                return;
            }

            page.departmentId = page.departmentId;

            page.storeModel = userStoreModel || new StoreModel({
                id: page.storeId
            });

            $.when(userStoreModel || page.storeModel.fetch()).then(function(){

                page.departmentModel = page.storeModel.departments.get(page.departmentId);

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