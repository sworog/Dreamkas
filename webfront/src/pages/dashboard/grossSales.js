define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        weekAgoDays = require('kit/weekAgoDays/weekAgoDays');

    return Block.extend({
        template: require('ejs!./grossSales.ejs'),
        resources: {
            grossSales: function() {
                return PAGE.resources.grossSales;
            }
        },
        weekAgoDay: function() {
            return weekAgoDays[moment(this.resources.grossSales.get('weekAgo').now.date).weekday()];
        }
    });

});