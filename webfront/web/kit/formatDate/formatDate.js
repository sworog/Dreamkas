define(function(require, exports, module) {
    //requirements
    var moment = require('moment');

    return function(date, opt){
        opt = _.extend({
            format: 'DD.MM.YYYY',
            time: false
        }, opt);

        if (typeof opt.time === 'string'){
            opt.format += ' ' + opt.time
        }

        if (opt.time === true) {
            opt.format += ' HH:mm'
        }

        return moment(date).format(opt.format);
    }
});