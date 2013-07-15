define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    return Table.extend({
        blockName: 'table_stores',
        templates: {
            head: require('tpl!blocks/table/table_stores/templates/head.html'),
            tr: require('tpl!blocks/table/table_stores/templates/tr.html')
        }
    });
});