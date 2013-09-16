define(function(require) {
        //requirements
        var InputDate = require('../inputDate/inputDate.js');

        return InputDate.extend({
            __name__: 'inputDate_noTime',
            className: 'inputDate inputDate_noTime',
            noTime: true
        });
    }
);