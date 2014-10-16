define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'reports',
        events: {
            'change select[name="store"]': function(e) {
                var storeId = e.target.value || undefined;

                this.setParams({
                    storeId: storeId
                });

                e.target.classList.add('loading');

                this.collections.groupStockSell.fetch({
                    filters: {
                        store: storeId
                    }
                }).then(function() {
                    e.target.classList.remove('loading');
                });
            },
            'update .inputDateRange': function(e) {

                var dateFromInput = e.target.querySelector('[name="dateFrom"]'),
                    dateToInput = e.target.querySelector('[name="dateTo"]'),
                    dateFrom = dateFromInput.value || undefined,
                    dateTo = dateToInput.value || undefined;

                this.setParams({
                    dateFrom: dateFrom,
                    dateTo: dateTo
                });

                dateFromInput.classList.add('loading');
                dateToInput.classList.add('loading');

                this.collections.groupStockSell.fetch({
                    filters: {
                        dateFrom: dateFrom,
                        dateTo: dateTo
                    }
                }).then(function() {
                    dateFromInput.classList.remove('loading');
                    dateToInput.classList.remove('loading');
                });
            }
        },
        collections: {
            stores: require('resources/store/collection'),
            groupStockSell: function() {
                var GroupStockSellCollection = require('resources/groupStockSell/collection');

                return new GroupStockSellCollection([], {
                    groupId: this.params.groupId,
                    filters: {
                        store: this.params.storeId,
                        dateFrom: this.params.dateFrom,
                        dateTo: this.params.dateTo
                    }
                });
            }
        },
        models: {
            group: function() {
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