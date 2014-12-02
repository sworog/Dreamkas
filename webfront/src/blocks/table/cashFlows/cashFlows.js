define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        template: require('ejs!./template.ejs'),
        models: {
            total: require('resources/cashFlowTotal/model')
        },
        collection: function() {
            return PAGE.collections.cashFlows;
        },
        blocks: {
            total: function(options) {
                var block = this,
                    TotalResults = require('blocks/totalResults/totalResults');

                options.model = block.models.total;

                options.caption1 = 'Приход';
                options.field1 = 'in';

                options.caption2 = 'Расход';
                options.field2 = 'out';

                options.caption3 = 'Баланс';
                options.field3 = 'balance';

                return new TotalResults(options);
            }
        },
        render: function() {
            var block = this;

            block.calculateTotal();

            block.groupedByDateList = block.collection.groupBy(function(cashFlow) {
                return block.formatDate(cashFlow.get('date'));
            });

            return Table.prototype.render.apply(block, arguments);
        },
        calculateTotal: function() {
            var block = this,
                total;

            total = {
                in: 0,
                out: 0
            };

            block.collection.forEach(function(cashFlow) {

                var amount = cashFlow.get('amount');

                if (cashFlow.get('direction') == 'in') {
                    total.in += amount;
                } else {
                    total.out += amount;
                }
            });

            total.balance = total.in - total.out;

            block.models.total.set(total);
        }
    });
});