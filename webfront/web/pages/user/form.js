define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Form_user = require('blocks/form/form_user/form_user'),
        UserModel = require('models/user');

    return Page.extend({
        pageName: 'page_user_form',
        initialize: function(userId, params) {
            var page = this;

            page.userId = userId;
        },
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        initModels: {
            user: function() {
                var page = this;

                return new UserModel({
                    id: page.userId
                });
            }
        },
        initBlocks: function() {
            var page = this;

            new Form_user({
                model: page.models.user,
                el: document.getElementById('form_user')
            });
        }
    });
});