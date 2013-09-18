define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Form_login = require('blocks/form/form_login/form_login');

    return Page.extend({
        __name__: 'page_common_login',
        partials: {
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