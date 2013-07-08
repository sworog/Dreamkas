define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    return Table.extend({
        blockName: 'table_products',
        templates: {
            head: require('tpl!blocks/table/table_balance/templates/head.html'),
            body: require('tpl!blocks/table/table_balance/templates/body.html')
        }
    });
});