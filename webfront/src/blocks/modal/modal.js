define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        checkKey = require('kit/checkKey/checkKey'),
        _ = require('lodash');

    $(document)
        .on('click', '[data-modal]', function(e) {
            var dataset = e.currentTarget.dataset,
                link = e.currentTarget,
                referrerModal = $(link).closest('.modal')[0];

            e.preventDefault();

            document.getElementById(dataset.modal).block.show(_.extend({
                referrer: referrerModal ? referrerModal.id : null
            }, dataset));
        })
        .on('click', '[data-modal-toggle]', function(e) {
            var dataset = e.currentTarget.dataset;

            e.preventDefault();

            document.getElementById(dataset.modalToggle).block.toggle();
        })
        .on('keyup', function(e) {
            var modal = $('.modal:visible')[0];
            checkKey(e.keyCode, ['ESC']) && modal && modal.block.hide();
        });

    return Block.extend({
        referrer: null,
        showDeletedMessage: true,
        events: {
            'click [data-modal-dialog]': function(e) {
                var block = this,
                    dialogSelector = e.target.dataset.modalDialog;

                block.$('.modal__dialog_visible')
                    .removeClass('modal__dialog_visible');

                block.$(dialogSelector)
                    .addClass('modal__dialog_visible');
            },
            'click .modal__closeLink': function(e) {
                var block = this;

                if (!e.target.dataset.referrer) {
                    block.hide();
                }
            }
        },
        initialize: function(data){

            data = data || {};

            if (typeof data.deleted === 'undefined') {
                this.deleted = false;
            }

            return Block.prototype.initialize.apply(this, arguments);
        },
        render: function() {
            var block = this,
                modal__wrapper = document.getElementById('modal__wrapper') || $('<div id="modal__wrapper"></div>').appendTo('body');

            Block.prototype.render.apply(block, arguments);

            $(modal__wrapper).append(block.$el);
        },
        show: function(data) {
            var block = this;

            block.initialize(data);

            document.body.classList.add('modal-open');

            block.toggle();

            block.trigger('shown');
        },
        toggle: function() {
            var block = this;

            block.$el
                .addClass('modal_visible')
                .siblings('.modal')
                .removeClass('modal_visible');

            document.getElementById('modal__wrapper').classList.add('modal__wrapper_visible');

            block.$('[autofocus]').focus();
        },
        hide: function(options) {
            var block = this;

            options = options || {};

            if (!options.submitSuccess &&
                block.isChanged() &&
                !confirm('Изменения не будут сохранены. Отменить изменения?'))
            {
                return;
            }

            this.close(options);
        },
        close: function(options) {
            var block = this;

            document.body.classList.remove('modal-open');

            block.el.classList.remove('modal_visible');

            document.getElementById('modal__wrapper').classList.remove('modal__wrapper_visible');

            block.reset();

            block.trigger('hidden');
        },
        reset: function() {
            this.$('form').each(function() {
                this.block && this.block.reset();
            });
        },
        isChanged: function() {
            var isChanged = false;

            this.$('form').each(function() {
                isChanged = isChanged || (this.block && this.block.isChanged());
            });

            return isChanged;
        }
    });
});