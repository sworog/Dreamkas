define(function(require) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        __name__: 'table_stores',
        templates: {
            head: require('ejs!blocks/table/table_stores/templates/head.html'),
            tr: require('ejs!blocks/table/table_stores/templates/tr.html')
        }
    });
});