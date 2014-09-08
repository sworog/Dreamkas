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
        render: function() {
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.$el.modal({
                show: false
            });
        },
        show: function(opt) {
            var block = this;

            block.set(opt);

            block.render();

            block.$el.modal('show');
        },
        hide: function() {
            var block = this;

            block.$el.modal('hide');
        }
    });
});