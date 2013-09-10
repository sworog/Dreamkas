define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Form_settings = require('blocks/form/form_settings/form_settings');

    return Page.extend({
        pageName: 'page_common_balance',
        templates: {
            '#content': require('tpl!./templates/settings.html')
        },
        permissions: {
            //products: 'GET'
        },
        initialize: function(){
            var page = this;

            page.render();

            new Form_settings({
                el: document.getElementById('form_settings')
            });
        }
    });
});