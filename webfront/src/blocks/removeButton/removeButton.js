define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        checkKey = require('kit/checkKey/checkKey');

    function resetButton(button) {
        button.classList.remove('removeButton_confirm');
        button.classList.remove('removeButton_fail');
    }

    $(document).on('click', function(e) {
        var $removeButton_confirm = $('.removeButton_confirm').not($(e.target).closest('.removeButton_confirm'));

        if ($removeButton_confirm.length) {
            resetButton($removeButton_confirm[0]);
        }
    });

    $(document).on('keyup', function(e) {
        var $removeButton_confirm = $('.removeButton_confirm').not($(e.target).closest('.removeButton_confirm'));

        if (checkKey(e.keyCode, ['ESC']) && $removeButton_confirm.length) {
            resetButton($removeButton_confirm[0]);
        }

    });

    return Block.extend({
        removeText: 'Удалить',
        confirmText: 'Подтвердить удаление',
        model: {},
        template: require('ejs!./template.ejs'),

        events: {
            'click .removeButton__trigger': function() {
                var block = this;

                block.el.classList.add('removeButton_confirm');
            },
            'click .removeButton__confirm': function() {
                var block = this;

                block.confirm();
            }
        },

        confirm: function() {
            var block = this,
                destroy = block.model.destroy && block.model.destroy();

            block.el.classList.add('removeButton_loading');

            if (destroy) {
                destroy.done(block.confirmDone.bind(block));
                destroy.fail(block.confirmFail.bind(block));
                destroy.always(block.confirmAlways.bind(block));
            }

            return destroy;
        },
        confirmDone: function() {

            var block = this;

            block.el.classList.add('removeButton_done');

        },
        confirmFail: function(response) {

            var block = this;
            
            block.el.classList.add('removeButton_fail');

            block.el.querySelector('.removeButton__error').textContent = response.responseJSON.message;
        },
        confirmAlways: function() {

            var block = this;

            block.el.classList.remove('removeButton_loading');
        }
    });
});