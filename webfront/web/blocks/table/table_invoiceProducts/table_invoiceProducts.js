define(function(require) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        __name__: 'table_invoiceProducts',
        templates: {
            head: require('tpl!blocks/table/table_invoiceProducts/templates/head.html'),
            tr: require('tpl!blocks/table/table_invoiceProducts/templates/tr.html')
        }
    });
});