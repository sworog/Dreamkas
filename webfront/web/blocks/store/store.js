define(function(require) {
        //requirements
        var Block = require('kit/block'),
            Table_departments = require('blocks/table/table_departments/table_departments'),
            Store__managers = require('blocks/store/store__managers');

        return Block.extend({
            blockName: 'store',
            storeModel: null,
            $departmentsTitle: null,
            storeManagersCollection: null,
            templates: {
                index: require('tpl!blocks/store/templates/index.html')
            },
            initialize: function(){
                var block = this;

                Block.prototype.initialize.apply(block, arguments);

                block.table_departments = new Table_departments({
                    collection: block.storeModel.departments,
                    el: document.getElementById('table_departments')
                });

                block.store__managers = new Store__managers({
                    storeManagersCollection: block.storeManagersCollection,
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
