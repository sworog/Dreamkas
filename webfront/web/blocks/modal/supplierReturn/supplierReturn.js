define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            supplierReturn: require('models/supplierReturn/supplierReturn')
        },
        events: {
            'click .supplierReturn__removeLink': function(e) {
                var block = this;

                e.target.classList.add('loading');

                block.models.supplierReturn.destroy().then(function() {
                    e.target.classList.remove('loading');
                });

            }
        },
        blocks: {
            form_supplierReturn: require('blocks/form/supplierReturn/supplierReturn'),
            form_supplierReturnProducts: require('blocks/form/supplierReturnProducts/supplierReturnProducts')
        }
    });
});