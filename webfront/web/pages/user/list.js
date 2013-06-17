define(function(require) {
    //requirements
    var content = require('blocks/content/content_main'),
        template = require('tpl!./list.html'),
        Table_users = require('blocks/table/_users/table_users');

    return function(){

        content.render(template);

        new Table_users({
            el: document.getElementById('table_users')
        });
    };
});