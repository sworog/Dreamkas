define(function(require, exports, module) {
    //requirements

    var CashFlowsCollection = require('resources/cashFlow/collection');
    var CashFlowsTable = require('./cashFlows');

    describe(module.id, function() {

        var cashFlowsCollection;
        var cashFlowsTable;

        beforeEach(function() {

            cashFlowsCollection = new CashFlowsCollection([
                {
                    amount: 1000,
                    direction: 'in'
                },
                {
                    amount: 2000,
                    direction: 'out'
                },
                {
                    amount: 10000,
                    direction: 'in'
                }
            ]);

            cashFlowsTable = new CashFlowsTable({
                collection: cashFlowsCollection,
                params: function() {
                    return {};
                }
            });
        });

        it('total calculation', function() {

            var totalModel = cashFlowsTable.models.total;

            expect(totalModel.get('in')).toBe(11000);
            expect(totalModel.get('out')).toBe(2000);
            expect(totalModel.get('balance')).toBe(9000);
        });
    });
});