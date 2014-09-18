define(function(require, exports, module) {
    //requirements
    var Autocomplete = require('blocks/autocomplete/autocomplete');

    return Autocomplete.extend({
        remoteUrl: '/products/search?properties[]=name&properties[]=sku&query=%QUERY',
        initTypeahead: function() {
            var block = this;

            block.$el.find('input').typeahead({
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

			block.$el.find('input.form-control').attr('name', 'product.name');
        }
    });
});