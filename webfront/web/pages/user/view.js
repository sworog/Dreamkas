define(function(require) {
    //requirements
    var Page = require('kit/page'),
        User = require('blocks/user/user'),
        UserModel = require('models/user'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/403/403');

    return Page.extend({
        pageName: 'page_user_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function() {
            var page = this;

            if (!(LH.isAllow('users', 'GET::{user}') || page.userId === 'current')){
                new Page403();
                return;
            }

            page.userModel = page.userId === 'current' ? currentUserModel : new UserModel({
                id: page.userId
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