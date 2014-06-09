define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        currentUser = require('models/currentUser.inst'),
        UserModel = require('models/user');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_users.ejs')
        },
        blocks: {
            form_user: function() {
                var Form_user = require('blocks/form/form_user/form_user');

                return new Form_user({
                    model: new UserModel({
                        id: currentUser.id
                    })
                });
            }
        }
    });
});
