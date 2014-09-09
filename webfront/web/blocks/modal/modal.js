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
                e.target.block.hide();
            }
        })
        .on('keyup', function(e){
            checkKey(e.keyCode, ['ESC']) && $('.modal:visible')[0].block.hide();
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
        },
        hide: function() {
            var block = this;

            block.$el.hide();
        }
    });
});