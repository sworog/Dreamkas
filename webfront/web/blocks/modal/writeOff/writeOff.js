define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            writeOff: require('models/writeOff/writeOff')
        },
        events: {
            'click .writeOff__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.models.writeOff.destroy().then(function() {
                    e.target.classList.remove('loading');
                });

            }
        },
        blocks: {
            form_writeOff: require('blocks/form/writeOff/writeOff'),
            form_writeOffProducts: require('blocks/form/writeOffProducts/writeOffProducts')
        }
    });
});