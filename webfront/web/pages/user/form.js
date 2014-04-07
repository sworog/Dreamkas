define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        Form_user = require('blocks/form/form_user/form_user'),
        UserModel = require('models/user');

    return Page.extend({
        __name__: 'page_user_form',
        params: {
            userId: null
        },
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            users: 'POST'
        },
        initialize: function() {
            var page = this;

            page.userModel = new UserModel({
                id: page.params.userId
            });

            $.when(page.params.userId ? page.userModel.fetch() : {}).then(function(){
                page.render();

                new Form_user({
                    model: page.userModel,
                    el: document.getElementById('form_user')
                });
            });

        }
    });
});