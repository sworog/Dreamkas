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
        models: {
            total: require('resources/cashFlowTotal/model')
        },
        collections: {
            cashFlows: function() {
                var page = this,
                    CashFlowsCollection = require('resources/cashFlow/collection'),
                    cashFlows;

                cashFlows = new CashFlowsCollection([], {
                    filters: _.pick(page.params, 'dateFrom', 'dateTo')
                });

                page.listenTo(cashFlows, {
                    'change add remove reset': function() {
                        page.calculateTotal();
                    }
                });

                return cashFlows;
            }
        },
        blocks: {
            modal_cashFlow: require('blocks/modal/cashFlow/cashFlow'),
            table_cashFlows: require('blocks/table/cashFlows/cashFlows'),
            inputDateRange: require('blocks/inputDateRange/inputDateRange'),
            total: function(options) {
                var block = this,
                    TotalResults = require('blocks/totalResults/totalResults');

                options.model = block.models.total;

                options.caption1 = 'Приход';
                options.field1 = 'in';

                options.caption2 = 'Уход';
                options.field2 = 'out';

                options.caption3 = 'Баланс';
                options.field3 = 'balance';

                return new TotalResults(options);
            }
        },
        initialize: function() {
            var page = this;

            page.params.dateTo = page.get('params.dateTo');
            page.params.dateFrom = page.get('params.dateFrom');

            return Page.prototype.initialize.apply(page, arguments);
        },
        calculateTotal: function() {
            var page = this,
                total;

            total = {
                in: 0,
                out: 0
            };

            page.collections.cashFlows.forEach(function(cashFlow) {

                var amount = cashFlow.get('amount');

                if (cashFlow.get('direction') == 'in') {
                    total.in += amount;
                } else {
                    total.out += amount;
                }
            });

            total.balance = total.in - total.out;

            page.models.total.set(total);
        }
    });
});
