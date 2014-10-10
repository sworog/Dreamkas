define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table'),
		URI = require('uri');

    return Table.extend({
        template: require('ejs!./template.ejs'),
        sortBy: 'name',
        collection: function(){
            return PAGE.collections.stockSell
        },
		groupUrl: function(groupModel) {

			return URI('/reports/stockSell/groups/' + groupModel.get('id'))
				.search(this.collection.filters).toString();
		}
    });
});