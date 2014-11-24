define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        template: require('ejs!./template.ejs'),
        collection: function() {
            return PAGE.collections.stockMovements;
        },
        render: function() {

            var block = this;

            this.groupedByDateList = this.collection.groupBy(function(stockMovement) {
                return block.formatDate(stockMovement.get('date'));
            });

            return Table.prototype.render.apply(this, arguments);
        }
    });
});