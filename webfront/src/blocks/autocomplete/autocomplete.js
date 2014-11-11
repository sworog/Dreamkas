define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        Tether = require('bower_components/tether/tether');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        placeholder: '',
        source: '',
        data: [],
        query: '',
        request: null,
        suggestionTemplate: function() {
        },
        autofocus: false,
        events: {
            'keyup .autocomplete__input': function(e) {

                var block = this,
                    input = e.target;

                block.query = input.value;

                if (input.value.length >= 3) {

                    block.$input.addClass('loading');

                    $.when(block.getData()).then(function() {

                        block.renderSuggestion();

                        block.$tetherElement.width(block.$input.outerWidth());
                        block.tether.enable();
                        block.tether.position();

                        block.$input.removeClass('loading');
                    });

                } else if (input.value.length) {
                    block.renderSuggestion();
                    block.$tetherElement.width(block.$input.outerWidth());
                    block.tether.enable();
                    block.tether.position();
                } else {
                    block.tether.disable();
                }

            },
            'blur .autocomplete__input': function() {

                var block = this;

                block.tether.disable();
            }
        },
        initialize: function() {
            var block = this;

            var init = Block.prototype.initialize.apply(block, arguments);

            block.source = block.get('source');

            return init;
        },
        render: function() {
            var block = this;

            var render = Block.prototype.render.apply(block, arguments);

            block.$tetherElement = block.$('.autocomplete__tether');
            block.$input = block.$('.autocomplete__input');

            block.tether = new Tether({
                element: block.$tetherElement,
                target: block.$input,
                attachment: 'top left',
                targetAttachment: 'bottom left',
                enabled: false
            });

            return render;
        },
        renderSuggestion: function() {

            var block = this;

            block.$tetherElement.html(block.suggestionTemplate(block));
        },
        getData: function() {
            var block = this;

            block.request && block.request.abort();

            if (typeof block.source === 'string') {
                block.request = $.ajax({
                    url: block.source,
                    data: {
                        query: block.query
                    }
                }).then(function(data) {
                    block.request = null;
                    block.data = data;
                });

                return block.request;
            }
        },
        remove: function() {

            var block = this;

            block.tether.destroy();
            block.$tetherElement.remove();

            return Block.prototype.remove.apply(block, arguments);
        }
    });
});