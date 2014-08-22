define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./modal_stockIn.ejs'),
        models: {
            stockIn: require('models/stockIn/stockIn')
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
                    block.$el.one('hidden.bs.modal', function(e) {
                        PAGE.render();
                    });

                    block.hide();
                });

                return form_stockIn;
            },
            form_stockInProducts: function(opt){
                var block = this,
                    Form_stockInProducts = require('blocks/form/form_stockInProducts/form_stockInProducts');

                return new Form_stockInProducts(_.extend(opt, {
                    models: {
                        stockIn: block.models.stockIn
                    }
                }));
            }
        }
    });
});