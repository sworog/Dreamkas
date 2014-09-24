define(function(require, exports, module) {
    //requirements
    var Autocomplete = require('blocks/autocomplete/autocomplete.deprecated');

    return Autocomplete.extend({
        el: '.autocomplete_products',
        remoteUrl: '/products/search?properties[]=name&properties[]=sku&query=%QUERY',
        initTypeahead: function() {
            var block = this;

            block.$el.typeahead({
                    highlight: true,
                    minLength: 3
                },
                {
                    source: block.engine.ttAdapter(),
                    displayKey: 'name',
                    templates: {
                        suggestion: require('ejs!./suggestion.ejs')
                    }
                });

        }
    });
});