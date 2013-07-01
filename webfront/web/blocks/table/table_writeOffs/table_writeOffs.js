define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    require('moment');

    return Table.extend({
        blockName: 'table_writeOffs',
        templates: {
            head: require('tpl!./templates/head.html'),
            body: require('tpl!./templates/body.html')
        }
    });
});