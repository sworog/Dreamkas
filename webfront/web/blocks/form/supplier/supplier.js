define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        collection: function(){
            return PAGE.get('collections.suppliers');
        },
        model: require('models/supplier/supplier')
    });
});