define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Table_users = require('blocks/table/table_users/table_users'),
        UsersCollection = require('collections/users');

    return Page.extend({
        templates: {
            content: require('tpl!./templates/list.html')
        },
        collections: {},
        initData: function(){
            this.collections.users = new UsersCollection();
        },
        initBlocks: function(){
            new Table_users({
                collection: this.collections.users,
                el: document.getElementById('table_users')
            });
        }
    });
});