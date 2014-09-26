define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        sortBy: 'product.name',
        template: require('ejs!./template.ejs'),
        collection: function(){
            return PAGE.collections.storeProducts;
        }
    });
});