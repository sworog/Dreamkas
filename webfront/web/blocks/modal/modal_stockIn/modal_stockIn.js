define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            stockIn: require('models/stockIn/stockIn')
        },
        events: {
            'click .stockIn__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.models.stockIn.destroy().then(function() {
                    e.target.classList.remove('loading');
                });

            }
        },
        blocks: {
            form_stockIn: function(opt){
                var block = this,
                    Form_stockIn = require('blocks/form/form_stockIn/form_stockIn');

                var form_stockIn = new Form_stockIn({
                    el: opt.el,
                    model: block.models.stockIn
                });

                form_stockIn.on('submit:success', function(){
                    block.hide();
                });

                return form_stockIn;
            },
            form_stockInProducts: function(opt){
                var block = this,
                    Form_stockInProducts = require('blocks/form/form_stockInProducts/form_stockInProducts');

                return new Form_stockInProducts({
                    el: opt.el,
                    models: {
                        stockIn: block.models.stockIn
                    }
                });
            }
        }
    });
});