define(function(require) {
    //requirements
    var Page = require('pages/page'),
        User = require('blocks/user/user'),
        UserModel = require('models/user');

    return Page.extend({
        pageName: 'page_user_view',
        initialize: function(userId) {
            var page = this;

            page.userId = userId;
        },
        templates: {
            '#content': require('tpl!./templates/view.html')
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

            new User({
                model: page.models.user,
                el: document.getElementById('user')
            });
        }
    });
});