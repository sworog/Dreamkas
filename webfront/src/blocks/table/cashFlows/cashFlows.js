define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        template: require('ejs!./template.ejs'),
        collection: function() {
            return PAGE.collections.cashFlows;
        },
        events: {
            'update .inputDateRange': function(e, data){

                e.target.classList.add('loading');
                PAGE.setParams(data);

                this.collection.fetch({
                    filters: data
                }).then(function(){
                    e.target.classList.remove('loading');
                });
            }
        },
        render: function() {
            var block = this;

            this.groupedByDateList = this.collection.groupBy(function(stockMovement) {
                return block.formatDate(stockMovement.get('date'));
            });

            return Table.prototype.render.apply(this, arguments);
        },
        blocks: {
            inputDateRange: require('blocks/inputDateRange/inputDateRange')
        }
    });
});