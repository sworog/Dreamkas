define(function(require) {
    //requirements
    var Table = require('kit/table/table');

    return Table.extend({
        blockName: 'table_products',
        templates: {
            head: require('tpl!./templates/head.html'),
            body: require('tpl!./templates/body.html')
        }
    });
});