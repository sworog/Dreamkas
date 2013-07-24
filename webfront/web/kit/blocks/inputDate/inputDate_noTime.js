define(function(require) {
        //requirements
        var InputDate = require('kit/blocks/inputDate/inputDate');

        return InputDate.extend({
            __name__: 'inputDate_noTime',
            className: 'inputDate inputDate_noTime',
            noTime: true
        });
    }
);