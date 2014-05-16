define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        Table_users = require('blocks/table/table_users/table_users'),
        UsersCollection = require('collections/users');

    return Page.extend({
        __name__: 'page_user_list',
        partials: {
            '#content': require('tpl!./templates/list.html')
        },
        permissions: {
            users: 'GET'
        },
        initialize: function(){
            var page = this;

            page.usersCollection = new UsersCollection();

            $.when(page.usersCollection.fetch()).then(function(){
                page.render();

                new Table_users({
                    collection: page.usersCollection,
                    el: document.getElementById('table_users')
                });
            });
        }
    });
});