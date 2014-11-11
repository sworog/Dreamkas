define(function(require, exports, module) {
    //requirements
    var Autocomplete = require('blocks/autocomplete/autocomplete'),
        config = require('config');

    return Autocomplete.extend({
        template: require('ejs!./template.ejs'),
        placeholder: 'Наименование или артикул товара',
        source: config.baseApiUrl + '/products/search?properties[]=name&properties[]=sku',
        suggestionTemplate: require('ejs!./suggestion.ejs')
    });
});