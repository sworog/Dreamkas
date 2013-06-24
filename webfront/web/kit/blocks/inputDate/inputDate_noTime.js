define(function(require) {
        //requirements
        var InputDate = require('kit/blocks/inputDate/inputDate');

        return InputDate.extend({
            noTime: true
        });
    }
);