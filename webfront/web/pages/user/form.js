define(function(require) {
    //requirements
    var $ = require('jquery'),
        Page = require('pages/page'),
        Form_user = require('blocks/form/form_user/form_user'),
        UserModel = require('models/user');

    return Page.extend({
        pageName: 'page_user_form',
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            users: 'POST'
        },
        initialize: function(userId, params) {
            var page = this;

            page.userId = userId;

            page.userModel = new UserModel({
                id: page.userId
            });

            $.when(userId ? page.userModel.fetch() : {}).then(function(){
                page.render();

                new Form_user({
                    model: page.userModel,
                    el: document.getElementById('form_user')
                });
            });

        }
    });
});