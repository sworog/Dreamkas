define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        _ = require('lodash');

    $(document).on('click', '[data-modal]', function(e) {
        var dataset = e.currentTarget.dataset;

        e.preventDefault();

        document.getElementById(dataset.modal).block.show(_.extend({}, dataset));
    });

    return Block.extend({
        events: {
            'click .close': function(){
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