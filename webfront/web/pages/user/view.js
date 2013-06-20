define(function(require) {
    //requirements
    var Page = require('kit/page'),
        User = require('blocks/user/user'),
        UserModel = require('models/user');

    return Page.extend({
        initialize: function(userId){
            var page = this;

            page.userId = userId;
        },
        templates: {
            content: require('tpl!./templates/view.html')
        },
        initModels: {
            user: function(){
                var page = this;

                return new UserModel({
                    id: page.userId
                });
            }
        },
        initBlocks: {
            user: function(){
                var page = this;

                return new User({
                    model: page.models.user,
                    el: document.getElementById('user')
                });
            }
        }
    });
});