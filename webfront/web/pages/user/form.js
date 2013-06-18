define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Form_user = require('blocks/form/form_user/form_user'),
        UserModel = require('models/user');

    return Page.extend({
        templates: {
            content: require('tpl!./templates/form.html')
        },
        models: {},
        initialize: function(userId, params) {
            var page = this;

            page.userId = userId;
            page.title = userId ? 'Редактирование пользователя' : 'Добавление нового пользователя';
        },
        initBlocks: function(){
            new Form_user({
                model: this.models.user,
                el: document.getElementById('form_user')
            });
        },
        initData: function(){
            this.models.user = new UserModel({
                id: this.userId
            });
        }
    });
});