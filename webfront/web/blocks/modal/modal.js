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
        .on('click', function(e) {
            if (e.target.classList.contains('modal__wrapper')) {
                $(e.target).find('.modal:visible')[0].block.hide();
            }
        })
        .on('keyup', function(e) {
            var modal = $('.modal:visible')[0];
            checkKey(e.keyCode, ['ESC']) && modal && modal.block.hide();
        });

    return Block.extend({
        events: {
            'click .modal__closeLink': function(e) {
                var block = this;

                if (!e.target.dataset.referrer) {
                    block.hide();
                }
            }
        },
        render: function() {
            var block = this,
                modal__wrapper = document.getElementById('modal__wrapper') || $('<div id="modal__wrapper"></div>').appendTo('body');

            Block.prototype.render.apply(block, arguments);

            $(modal__wrapper).append(block.$el);
        },
        show: function(data) {
            var block = this;

            block.render(data);

            document.body.classList.add('modal-open');

            block.$el
                .addClass('modal_visible')
                .siblings('.modal')
                .removeClass('modal_visible');

            document.getElementById('modal__wrapper').classList.add('modal__wrapper_visible');

            block.$('[autofocus]').focus();

            block.trigger('shown');
        },
        hide: function() {
            var block = this;

            document.body.classList.remove('modal-open');

            block.el.classList.remove('modal_visible');

            document.getElementById('modal__wrapper').classList.remove('modal__wrapper_visible');

            block.trigger('hidden');
        }
    });
});