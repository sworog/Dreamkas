define(function(require) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        __name__: 'table_products',
        templates: {
            head: require('tpl!blocks/table/table_balance/templates/head.html'),
            tr: require('tpl!blocks/table/table_balance/templates/tr.html')
        }
    });
});