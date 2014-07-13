define(function(require) {
    //requirements
    var Table = require('blocks/table/table'),
        moment = require('moment');

    return Table.extend({
        __name__: 'table_writeOffs',
        templates: {
            head: require('ejs!blocks/table/table_writeOffs/templates/head.html'),
            tr: require('ejs!blocks/table/table_writeOffs/templates/tr.html')
        }
    });
});