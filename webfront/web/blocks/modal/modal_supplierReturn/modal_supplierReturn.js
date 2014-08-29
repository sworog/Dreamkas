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
            form_supplierReturn: function(opt) {
                var block = this,
                    Form_supplierReturn = require('blocks/form/supplierReturn/supplierReturn');

                var form_supplierReturn = new Form_supplierReturn({
                    el: opt.el,
                    model: block.models.supplierReturn
                });

                form_supplierReturn.on('submit:success', function() {
                    block.hide();
                });

                return form_supplierReturn;
            },
            form_supplierReturnProducts: function(opt) {
                var block = this,
                    Form_stockInProducts = require('blocks/form/supplierReturnProducts/supplierReturnProducts');

                return new Form_stockInProducts({
                    el: opt.el,
                    models: {
                        supplierReturn: block.models.supplierReturn
                    }
                });
            }
        }
    });
});