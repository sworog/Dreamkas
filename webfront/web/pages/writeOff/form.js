define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Form_writeOff = require('blocks/form/form_writeOff/form_writeOff'),
        WriteOffModel = require('models/writeOff');

    return Page.extend({
        __name__: 'page_writeOff_form',
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            writeoffs: 'POST'
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