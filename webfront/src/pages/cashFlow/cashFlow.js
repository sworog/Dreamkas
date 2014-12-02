define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'cashFlows',
        params: {
            dateTo: function() {
                var page = this,
                    currentTime = Date.now();

                return page.formatDate(moment(currentTime));
            },
            dateFrom: function() {
                var page = this,
                    currentTime = Date.now();

                return page.formatDate(moment(currentTime).subtract(1, 'month'));
            }
        },
        collections: {
            cashFlows: function() {
                var page = this,
                    CashFlowsCollection = require('resources/cashFlow/collection');

                return new CashFlowsCollection([], {
                    filters: _.pick(page.params, 'dateFrom', 'dateTo', 'types')
                });
            }
        },
        blocks: {
            modal_cashFlow: require('blocks/modal/cashFlow/cashFlow'),
            table_cashFlows: require('blocks/table/cashFlows/cashFlows')
        },
        initialize: function(){

            this.params.dateTo = this.get('params.dateTo');
            this.params.dateFrom = this.get('params.dateFrom');

            return Page.prototype.initialize.apply(this, arguments);
        }
    });
});
