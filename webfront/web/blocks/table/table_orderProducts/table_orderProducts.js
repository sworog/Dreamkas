define(function(require) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        __name__: 'table_orderProducts',
        templates: {
            head: require('tpl!blocks/table/table_orderProducts/templates/head.html'),
            tr: require('tpl!blocks/table/table_orderProducts/templates/tr.html')
        }
    });
});