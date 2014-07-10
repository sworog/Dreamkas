define(function(require, exports, module) {
    //requirements
    var moment = require('moment');

    return function(date, opt){
        opt = _.extend({
            format: 'DD.MM.YYYY',
            time: false
        }, opt);

        return moment(date).format(opt.format);
    }
});