define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        ProductModel = require('models/product/product');

    return Block.extend({
        el: '.modal_writeOff',
        collections: {
            stockMovements: null
        },
        blocks: {
            form_writeOff: function() {
                var block = this,
                    Form_writeOff = require('blocks/form/form_writeOff/form_writeOff'),
                    form_writeOff = new Form_writeOff({
                        el: block.$('.form_writeOff'),
                        collection: block.collections.stockMovements
                    });

                form_writeOff.on('submit:success', function(){
                    block.$el.modal('hide');
                });

                return form_writeOff;
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);
        }
    });
});