define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    return Table.extend({
        __name__: 'table_writeOffProducts',
        templates: {
            head: require('tpl!blocks/table/table_writeOffProducts/templates/head.html'),
            tr: require('tpl!blocks/table/table_writeOffProducts/templates/tr.html')
        }
    });
});