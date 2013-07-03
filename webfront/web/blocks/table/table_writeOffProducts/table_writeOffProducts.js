define(function(require) {
    //requirements
    var Table = require('kit/blocks/table/table');

    return Table.extend({
        blockName: 'table_writeOffProducts',
        templates: {
            head: require('tpl!./templates/head.html'),
            body: require('tpl!./templates/body.html')
        }
    });
});