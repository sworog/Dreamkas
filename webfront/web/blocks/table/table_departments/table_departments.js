define(function(require) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        __name__: 'table_stores',
        templates: {
            head: require('tpl!blocks/table/table_departments/templates/head.html'),
            tr: require('tpl!blocks/table/table_departments/templates/tr.html')
        }
    });
});