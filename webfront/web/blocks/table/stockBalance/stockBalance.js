define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        sortBy: 'product.name',
        template: require('ejs!./template.ejs'),
        collection: function(){
            return PAGE.collections.storeProducts;
        },
        highlight: function(string){

            var block = this,
                query = block.collection.filters.query,
                result = _.escape(string);

            if (query){
                result = _.escape(string).replace(new RegExp(query, 'gi'), '<b>' + query + '</b>');
            }

            return result;
        }
    });
});