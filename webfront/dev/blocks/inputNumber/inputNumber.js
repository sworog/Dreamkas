define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
        checkKey = require('kit/checkKey/checkKey');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        name: 'quantity',
        value: '',
        step: 1,
        events: {
            'click .inputNumber__countUp': function(e) {
                e.preventDefault();

                var block = this;

                block.changeValue(this.step);
            },
            'click .inputNumber__countDown': function(e) {
                e.preventDefault();

                var block = this;

                block.changeValue(-this.step);
            },
            'keyup': function(e) {
                e.stopPropagation();

                var block = this,
                    delta;

                if (checkKey(e.keyCode, ['UP'])) {
                    delta = this.step;
                }

                if (checkKey(e.keyCode, ['DOWN'])) {
                    delta = -this.step;
                }

                block.changeValue(delta);
            }
        },
        changeValue: function(delta) {

            var input = this.el.querySelector('input');

            if (delta) {
                input.value = this.formatMoney(normalizeNumber(input.value) + delta)
            }

            this.value = input.value;

            this.trigger('change', input.value);
        }
    });
});