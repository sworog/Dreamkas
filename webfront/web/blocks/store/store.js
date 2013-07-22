define(function(require) {
        //requirements
        var Block = require('kit/block'),
            Table_departments = require('blocks/table/table_departments/table_departments');

        return Block.extend({
            blockName: 'store',
            storeModel: null,
            $departmentsTitle: null,
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
