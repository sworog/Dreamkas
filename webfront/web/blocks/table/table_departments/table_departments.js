define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    return Table.extend({
        blockName: 'table_stores',
        templates: {
            head: require('tpl!blocks/table/table_departments/templates/head.html'),
            tr: require('tpl!blocks/table/table_departments/templates/tr.html')
        }
    });
});