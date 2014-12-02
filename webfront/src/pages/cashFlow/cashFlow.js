define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'cashFlows',
        events: {
            'update .inputDateRange': function(e, data){

                e.target.classList.add('loading');
                this.setParams(data);

                this.collections.cashFlows.fetch({
                    filters: data
                }).then(function(){
                    e.target.classList.remove('loading');
                });
            }
        },
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
                    CashFlowsCollection = require('resources/cashFlow/collection'),
                    cashFlows;

                cashFlows = new CashFlowsCollection([], {
                    filters: _.pick(page.params, 'dateFrom', 'dateTo')
                });

                return cashFlows;
            }
        },
        blocks: {
            modal_cashFlow: require('blocks/modal/cashFlow/cashFlow'),
            table_cashFlows: require('blocks/table/cashFlows/cashFlows')
        },
        initialize: function() {
            var page = this;

            page.params.dateTo = page.get('params.dateTo');
            page.params.dateFrom = page.get('params.dateFrom');

            return Page.prototype.initialize.apply(page, arguments);
        }
    });
});
