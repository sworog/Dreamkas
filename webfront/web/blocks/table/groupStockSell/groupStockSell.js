define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        template: require('ejs!./template.ejs'),
        sortBy: 'product.name',
        collection: function() {
            return PAGE.collections.stockSell;
        },
        render: function() {
            var totalGrossSales = 0,
                totalGrossMargin = 0;

            this.collection.forEach(function(productStockSellModel) {
                totalGrossSales += productStockSellModel.get('grossSales');
                totalGrossMargin += productStockSellModel.get('grossMargin');
            });

            this.totalGrossSales = totalGrossSales;
            this.totalGrossMargin = totalGrossMargin;

            Table.prototype.render.apply(this, arguments);
        },
        calculateGrossSalesPercent: function(grossSales) {
            var percent = 100 * grossSales / this.totalGrossSales;

            return this.formatNumber(percent.toFixed(1));
        },
        calculateGrossMarginPercent: function(grossMargin) {
            var percent = 100 * grossMargin / this.totalGrossMargin;

            return this.formatNumber(percent.toFixed(1));
        }
    });
});