define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Table_users = require('blocks/table/table_users/table_users'),
        UsersCollection = require('collections/users');

    return Page.extend({
        templates: {
            '#content': require('tpl!./templates/list.html')
        },
        initCollections: {
            users: function() {
                return new UsersCollection();
            }
        },
        initBlocks: function() {
            var page = this;

            new Table_users({
                collection: page.collections.users,
                el: document.getElementById('table_users')
            });
        }
    });
});