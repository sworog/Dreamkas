define(function(require) {
    //requirements
    var Table = require('kit/table/table'),
        UsersCollection = require('collections/users');

    return Table.extend({
        Collection: UsersCollection,
        blockName: 'table_users',
        templates: {
            head: require('tpl!./templates/head.html'),
            body: require('tpl!./templates/body.html')
        }
    });
});