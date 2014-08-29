define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            group: require('models/group/group')
        },
        blocks: {
            form_group: function(opt){
                var block = this,
                    Form_group = require('blocks/form/group/group'),
                    form_group = new Form_group({
                        el: opt.el
                    });

                form_group.on('submit:success', function(){
                    block.hide();
                });
            }
        }
    });
});