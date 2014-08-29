define(function(require, exports, module) {
    //requirements
    var Select = require('blocks/select/select');

    return Select.extend({
        collection: function(){
            return PAGE.collections.suppliers
        },
        template: require('ejs!./template.ejs')
    });
});