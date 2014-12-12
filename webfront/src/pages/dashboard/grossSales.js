define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        moment = require('moment');

    return Block.extend({
        template: require('ejs!./grossSales.ejs'),
        resources: {
            grossSales: function() {
                return PAGE.resources.grossSales;
            }
        },
        weekAgoDay: function() {
            return moment(this.resources.grossSales.get('weekAgo').now.date).subtract(6, 'days').calendar();
        }
    });

});