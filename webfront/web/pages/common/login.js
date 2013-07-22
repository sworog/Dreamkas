define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Form_login = require('blocks/form/form_login/form_login');

    return Page.extend({
        pageName: 'page_common_login',
        templates: {
            '#content': require('tpl!./templates/login.html')
        },
        initialize: function(){
            var page = this;

            page.render();

            new Form_login({
                el: document.getElementById('form_login')
            });
        }
    });
});