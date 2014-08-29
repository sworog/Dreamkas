define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        collection: function(){
            return PAGE.collections.suppliers;
        },
        model: require('models/supplier/supplier')
    });
});