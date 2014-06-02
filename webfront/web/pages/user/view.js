define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        User = require('blocks/user/user'),
        UserModel = require('models/user'),
        currentUserModel = require('models/currentUser.inst'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_user_view',
        params: {
            userId: null
        },
        partials: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function() {
            var page = this;

            if (!(LH.isAllow('users', 'GET::{user}') || page.params.userId === 'current')){
                new Page403();
                return;
            }

            page.userModel = page.userId === 'current' ? currentUserModel : new UserModel({
                id: page.params.userId
            });

            $.when(page.userModel.fetch()).then(function(){
                page.render();

                new User({
                    model: page.userModel,
                    el: document.getElementById('user')
                });
            });
        }
    });
});