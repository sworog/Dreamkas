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
            var modal = $('.modal:visible')[0].block;

            checkKey(e.keyCode, ['ESC']) && modal && modal.hide();
        });

    return Block.extend({
        events: {
            'click .close': function() {
                var block = this;

                block.hide();
            }
        },
        show: function(data) {
            var block = this;

            block.render(data);

            block.$el
                .show()
                .find('[autofocus]').focus();

            block.$el.trigger('modal.shown');
        },
        hide: function() {
            var block = this;

            block.$el.hide();

            block.$el.trigger('modal.hidden');
        }
    });
});