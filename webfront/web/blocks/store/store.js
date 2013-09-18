define(function(require) {
        //requirements
        var Block = require('kit/core/block'),
            Table_departments = require('blocks/table/table_departments/table_departments'),
            Store__managers = require('blocks/store/store__managers');

        return Block.extend({
            __name__: 'store',
            storeModel: null,
            $departmentsTitle: null,
            storeManagerCandidatesCollection: null,
            storeManagersCollection: null,
            template: require('tpl!blocks/store/store.html'),
            initialize: function(){
                var block = this;

                block.table_departments = new Table_departments({
                    collection: block.storeModel.departments,
                    el: document.getElementById('table_departments')
                });

                block.store__managers = new Store__managers({
                    storeManagerCandidatesCollection: block.storeManagerCandidatesCollection,
                    storeManagersCollection: block.storeManagersCollection,
                    storeModel: block.storeModel,
                    el: document.getElementById('store__managers')
                });

                if (block.storeModel.departments.length){
                    block.$departmentsTitle.html(LH.text('Отделы'));
                    block.table_departments.$el.show();
                } else {
                    block.$departmentsTitle.html(LH.text('Нет отделов'));
                    block.table_departments.$el.hide();
                }
            }
        })
    }
);
