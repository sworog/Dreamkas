define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Table_users = require('blocks/table/table_users/table_users'),
        UsersCollection = require('collections/users');

    return Page.extend({
        pageName: 'page_user_list',
        templates: {
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