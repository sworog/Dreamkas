define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    require('moment');

    return Table.extend({
        __name__: 'table_invoices',
        templates: {
            head: require('tpl!blocks/table/table_invoices/templates/head.html'),
            tr: require('tpl!blocks/table/table_invoices/templates/tr.html')
        }
    });
});