define(function(require, exports, module) {
    //requirements
    var Autocomplete = require('blocks/autocomplete/autocomplete');

    return Autocomplete.extend({
        template: require('ejs!./template.ejs'),
        placeholder: 'Наименование или артикул товара',
        source: '/products/search?properties[]=name&properties[]=sku',
        suggestionTemplate: require('ejs!./suggestion.ejs'),
        events: {
            'keyup .autocomplete__input': function(e){

                var block = this,
                    input = e.target;

                if (input.value.length){
                    block.$tetherElement.width(block.$input.outerWidth());
                    block.tether.enable();
                    block.tether.position();
                } else {
                    block.tether.disable();
                }

            },
            'blur .autocomplete__input': function(){

                var block = this;

                block.tether.disable();
            }
        }
    });
});