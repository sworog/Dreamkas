define(function(require) {
        //requirements
        var Block = require('kit/core/block'),
            Table_departments = require('blocks/table/table_departments/table_departments'),
            Store__storeManagers = require('blocks/store/store__storeManagers'),
            Store__departmentManagers = require('blocks/store/store__departmentManagers');

        return Block.extend({
            __name__: 'store',
            storeModel: null,
            $departmentsTitle: null,
            storeManagerCandidatesCollection: null,
            storeManagersCollection: null,
            departmentManagerCandidatesCollection: null,
            departmentManagersCollection: null,
            template: require('tpl!blocks/store/store.html'),
            initialize: function(){
                var block = this;

                block.table_departments = new Table_departments({
                    collection: block.storeModel.departments,
                    el: document.getElementById('table_departments')
                });

                block.store__storeManagers = new Store__storeManagers({
                    storeManagerCandidatesCollection: block.storeManagerCandidatesCollection,
                    storeManagersCollection: block.storeManagersCollection,
                    storeModel: block.storeModel,
                    el: document.getElementById('store__storeManagers')
                });

                block.store__departmentManagers = new Store__departmentManagers({
                    departmentManagerCandidatesCollection: block.departmentManagerCandidatesCollection,
                    departmentManagersCollection: block.departmentManagersCollection,
                    storeModel: block.storeModel,
                    el: document.getElementById('store__departmentManagers')
                });


                if (block.storeModel.departments.length){
                    block.$departmentsTitle.html('Отделы');
                    block.table_departments.$el.show();
                } else {
                    block.$departmentsTitle.html('Нет отделов');
                    block.table_departments.$el.hide();
                }
            }
        })
    }
);
