define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Form_user = require('blocks/form/form_user/form_user'),
        UserModel = require('models/user');

    return Page.extend({
        pageName: 'page_user-form',
        templates: {
            content: require('tpl!./templates/form.html')
        },
        initialize: function(userId, params){
            var page = this,
                userModel = new UserModel({
                    id: userId
                });

            page.title = userId ? 'Редактирование пользователя' : 'Добавление нового пользователя';

            page.render();

            new Form_user({
                model: userModel,
                el: document.getElementById('form_user')
            });

            if (userModel.id){
                userModel.fetch();
            }
        }
    });
});