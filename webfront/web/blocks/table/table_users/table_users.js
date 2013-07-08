define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    return Table.extend({
        blockName: 'table_users',
        templates: {
            head: require('tpl!blocks/table/table_users/templates/head.html'),
            body: require('tpl!blocks/table/table_users/templates/body.html')
        }
    });
});