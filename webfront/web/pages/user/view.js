define(function(require) {
    //requirements
    var content = require('blocks/content/content_main'),
        template = require('tpl!./templates/view.html'),
        User = require('blocks/user/user');

    return function(userId){

        content.render(template);

        new User({
            userId: userId,
            el: document.getElementById('user')
        });
    };
});