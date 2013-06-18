define(function(require) {
    //requirements
    var content = require('blocks/content/content_main'),
        template = require('tpl!./list.html'),
        Table_users = require('blocks/table/table_users/table_users'),
        UsersCollection = require('collections/users');

    return function(){

        var userCollection = new UsersCollection();

        content.render(template);

        new Table_users({
            collection: userCollection,
            el: document.getElementById('table_users')
        });

        userCollection.fetch();
    };
});