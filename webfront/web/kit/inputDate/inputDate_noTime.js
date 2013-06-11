define(function(require) {
        //requirements
        var InputDate = require('kit/inputDate/inputDate');

        return InputDate.extend({
            noTime: true
        });
    }
);