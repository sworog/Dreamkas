define(function(require) {
    //requirements
    var Page = require('pages/page'),
        content = require('blocks/content/content_main'),
        template = require('tpl!./form.html'),
        Form_user = require('blocks/form/form_user/form_user');

    return Page.extend({
        pageName: 'user_editor',
        initialize: function(userId, params){
            console.log(this);
        }
    });
});