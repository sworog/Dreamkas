define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./mainInfo.ejs'),
        weekAgoDays: {
            0: 'прошлый понедельник',
            1: 'прошлый вторник',
            2: 'прошлую среду',
            3: 'прошлый четверг',
            4: 'прошлую пятницу',
            5: 'прошлую субботу',
            6: 'прошлое воскресенье'
        },
        weekAgoDay: function() {
            return this.weekAgoDays[moment(this.resource.get('today').now.date).weekday()];
        }
    });
});