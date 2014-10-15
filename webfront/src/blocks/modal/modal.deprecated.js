define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        checkKey = require('kit/checkKey/checkKey'),
        _ = require('lodash');

    $(document)
        .on('click', '[data-modal]', function(e) {
            var dataset = e.currentTarget.dataset;

            e.preventDefault();

            document.getElementById(dataset.modal).block.show(_.extend({}, dataset));
        })
        .on('click', function(e){
            if (e.target.classList.contains('modal')){
                e.target.block && e.target.block.hide();
            }
        })
        .on('keyup', function(e){
            var modal = $('.modal:visible')[0];

            checkKey(e.keyCode, ['ESC']) && modal && modal.block && modal.block.hide();
        });

    return Block.extend({
        events: {
            'click .close': function() {
                var block = this;

                block.hide();
            }
        },
        render: function(){
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.$el.appendTo('.page');
        },
        show: function(data) {
            var block = this;

            block.render(data);

            document.body.classList.add('modal-open');

            block.$el
                .show()
                .find('[autofocus]').focus();

            block.$el.trigger('modal.shown');
        },
        hide: function() {
            var block = this;

            document.body.classList.remove('modal-open');

            block.$el.hide();

            block.$el.trigger('modal.hidden');
        }
    });
});