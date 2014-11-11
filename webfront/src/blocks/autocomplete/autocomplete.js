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
        suggestionTemplate: function(){},
        autofocus: false,
        blocks: {
            suggestion: function(){
                return new Block({
                    data: this.data,
                    query: this.query,
                    template: this.suggestionTemplate
                });
            }
        },
        initialize: function(){
            var block = this;

            var init = Block.prototype.initialize.apply(block, arguments);

            block.source = block.get('source');

            return init;
        },
        render: function(){
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
        getData: function(){
            var block = this;

            block.request && block.request.abort();

            if (typeof block.source === 'string'){
                block.request = $.ajax({
                    url: block.source,
                    data: {
                        query: block.query
                    }
                });

                return block.request;
            }
        },
        remove: function(){

            var block = this;

            block.tether.destroy();
            block.$tetherElement.remove();

            return Block.prototype.remove.apply(block, arguments);
        }
    });
});