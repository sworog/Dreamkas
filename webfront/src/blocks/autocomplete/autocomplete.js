define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        Tether = require('bower_components/tether/tether'),
        checkKey = require('kit/checkKey/checkKey');

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

                if (checkKey(e.keyCode, ['ESC'])) {
                    block.hideSuggestion();
                    return;
                }

                if (checkKey(e.keyCode, ['UP'])) {
                    block.focusPrevItem();
                    return false;
                }

                if (checkKey(e.keyCode, ['DOWN'])) {

                    if (block.$tetherElement.is(':hidden')){
                        block.showSuggestion();
                    }

                    block.focusNextItem();
                    return false;
                }

                block.query = input.value;

                if (input.value.length >= 3) {

                    block.getData();

                } else if (input.value.length) {
                    block.showSuggestion();
                } else {
                    block.hideSuggestion();
                }

            },
            'blur .autocomplete__input': function() {

                var block = this;

                block.hideSuggestion();
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

            block.$tetherElement.on('mouseover', '.autocomplete__item', function(){
                var index = block.$tetherElement.find('.autocomplete__item').index(this);

                block.focusItemByIndex(index);
            });

            return render;
        },
        focusNextItem: function() {

            var block = this,
                $currentFocusedItem = block.$tetherElement.find('.autocomplete__item_focused'),
                currentFocusedItemIndex = block.$tetherElement.find('.autocomplete__item').index($currentFocusedItem);

            block.focusItemByIndex(currentFocusedItemIndex + 1);
        },
        focusPrevItem: function() {

            var block = this,
                $currentFocusedItem = block.$tetherElement.find('.autocomplete__item_focused'),
                currentFocusedItemIndex = block.$tetherElement.find('.autocomplete__item').index($currentFocusedItem);

            block.focusItemByIndex(currentFocusedItemIndex - 1);
        },
        focusItemByIndex: function(index) {

            var block = this,
                itemToFocus = block.$tetherElement.find('.autocomplete__item')[index];

            $(itemToFocus)
                .addClass('autocomplete__item_focused')
                .siblings('.autocomplete__item')
                .removeClass('autocomplete__item_focused');

        },
        showSuggestion: function() {

            var block = this;

            block.$tetherElement.html(block.suggestionTemplate(block));

            block.$tetherElement.width(block.$input.outerWidth());
            block.tether.enable();
            block.tether.position();
        },
        hideSuggestion: function() {

            var block = this;

            block.tether.disable();
        },
        getData: function() {
            var block = this;

            block.request && block.request.abort();

            if (typeof block.source === 'string') {

                block.$input.addClass('loading');

                block.request = $.ajax({
                    url: block.source,
                    data: {
                        query: block.query
                    }
                }).then(function(data) {

                    block.request = null;
                    block.data = data;

                    block.showSuggestion();

                    block.$input.removeClass('loading');
                });

                return block.request;
            }
        },
        select: function(index) {

            var block = this,
                itemData = block.data[index];

            block.trigger('select', itemData);


        },
        remove: function() {

            var block = this;

            block.tether.destroy();
            block.$tetherElement.remove();

            return Block.prototype.remove.apply(block, arguments);
        }
    });
});