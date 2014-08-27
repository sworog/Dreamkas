define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./modal_writeOff.ejs'),
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
            form_writeOff: function(opt){
                var block = this,
                    Form_writeOff = require('blocks/form/form_writeOff/form_writeOff');

                var form_writeOff = new Form_writeOff({
                    el: opt.el,
                    model: block.models.writeOff
                });

                form_writeOff.on('submit:success', function(){
                    block.hide();
                });

                return form_writeOff;
            },
            form_writeOffProducts: function(opt){
                var block = this,
                    Form_writeOffProducts = require('blocks/form/form_writeOffProducts/form_writeOffProducts');

                return new Form_writeOffProducts({
                    el: opt.el,
                    models: {
                        writeOff: block.models.writeOff
                    }
                });
            }
        }
    });
});