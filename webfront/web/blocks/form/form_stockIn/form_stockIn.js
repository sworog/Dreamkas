define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        template: require('ejs!./form_stockIn.ejs')
    });
});