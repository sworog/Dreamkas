define(function(require) {
    //requirements
    var Table = require('blocks/table/table'),
        moment = require('moment');

    return Table.extend({
        __name__: 'table_invoices',
        templates: {
            head: require('tpl!blocks/table/table_invoices/templates/head.html'),
            tr: require('tpl!blocks/table/table_invoices/templates/tr.html')
        }
    });
});