define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block.deprecated'),
        ProductModel = require('resources/product/model');

    return Block.extend({
        el: '.modal_writeOff',
        collections: {
            stockMovements: null
        },
        models: {
            writeOff: null
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
            form_writeOff: function() {
                var block = this,
                    WriteOffModel = require('resources/writeOff/model'),
                    Form_writeOff = require('blocks/form/form_writeOff/form_writeOff'),
                    form_writeOff = new Form_writeOff({
                        el: block.$('.form_writeOff'),
                        collection: block.collections.stockMovements,
                        model: block.models.writeOff || new WriteOffModel()
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