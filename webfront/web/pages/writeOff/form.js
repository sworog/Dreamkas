define(function(require) {
    //requirements
    var $ = require('jquery'),
        Page = require('pages/page'),
        Form_writeOff = require('blocks/form/form_writeOff/form_writeOff'),
        WriteOffModel = require('models/writeOff');

    return Page.extend({
        pageName: 'page_writeOff_form',
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            writeoffs: 'all'
        },
        initialize: function() {
            var page = this;

            page.writeOffModel = new WriteOffModel();

            page.render();

            new Form_writeOff({
                model: page.writeOffModel,
                el: document.getElementById('form_writeOff')
            });

        }
    });
});