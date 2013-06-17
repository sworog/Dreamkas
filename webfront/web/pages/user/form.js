define(function(require) {
    //requirements
    var content = require('blocks/content/content_main'),
        template = require('tpl!./form.html'),
        Form_user = require('blocks/form/_user/form_user');

    return function(userId){
        content.render(template, {
            title: userId ? 'Редактирование пользователя' : 'Добавление нового пользователя'
        });

        new Form_user({
            modelId: userId,
            el: document.getElementById('form_user')
        });
    };
});