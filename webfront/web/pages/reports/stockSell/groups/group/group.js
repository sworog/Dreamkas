define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'reports',
        events: {
            'change select[name="store"]': function(e){
                var storeId = e.target.value || undefined;

                this.setParams({
                    storeId: storeId
                });

                e.target.classList.add('loading');

                this.collections.groupStockSell.filter({
                    store: storeId
                }).then(function(){
                    e.target.classList.remove('loading');
                });
            }
        },
        collections: {
            stores: require('resources/store/collection'),
            groupStockSell: function(){
                var GroupStockSellCollection = require('resources/groupStockSell/collection');

                return new GroupStockSellCollection([], {
                    groupId: this.params.groupId
                });
            }
        },
        models: {
            group: function(){
                var GroupModel = require('resources/group/model');

                return new GroupModel({
                    id: this.params.groupId
                });
            }
        },
        blocks: {
            select_store: require('blocks/select/store/store'),
            inputDateRange: require('blocks/inputDateRange/inputDateRange'),
            table_groupStockSell: require('blocks/table/groupStockSell/groupStockSell')
        }
    });
});