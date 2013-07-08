define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    return Table.extend({
        blockName: 'table_invoiceProducts',
        templates: {
            head: require('tpl!blocks/table/table_invoiceProducts/templates/head.html'),
            body: require('tpl!blocks/table/table_invoiceProducts/templates/body.html')
        }
    });
});