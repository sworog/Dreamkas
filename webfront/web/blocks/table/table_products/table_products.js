define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    return Table.extend({
        blockName: 'table_products',
        templates: {
            head: require('tpl!blocks/table/table_products/templates/head.html'),
            tr: require('tpl!blocks/table/table_products/templates/tr.html')
        }
    });
});