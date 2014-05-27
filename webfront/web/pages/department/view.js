define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        Department = require('blocks/department/department'),
        getUserStore = require('kit/getUserStore/getUserStore'),
        StoreModel = require('models/store'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_department_view',
        params: {
            storeId: null,
            departmentId: null
        },
        partials: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function() {
            var page = this,
                userStoreModel = getUserStore(page.params.storeId);

            if (!(LH.isAllow('departments', 'GET::{department}') || userStoreModel)){
                new Page403();
                return;
            }

            page.storeModel = userStoreModel || new StoreModel({
                id: page.params.storeId
            });

            $.when(userStoreModel || page.storeModel.fetch()).then(function(){

                page.departmentModel = page.storeModel.departments.get(page.params.departmentId);

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