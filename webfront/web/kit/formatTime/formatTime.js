define(function(require, exports, module) {
    //requirements
    var moment = require('moment');

    return function(date, opt){
        opt = _.extend({
            format: 'HH:mm'
        }, opt);

        return moment(date).format(opt.format);
    }
});