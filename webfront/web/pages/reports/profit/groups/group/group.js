define(function(require, exports, module) {
    //requirements
	var Page_productsProfit = require('blocks/page/productsProfit/productsProfit');

    return Page_productsProfit.extend({
        content: require('ejs!./content.ejs'),
        ProfitCollection: require('resources/productsProfit/collection'),
        models: {
            group: function() {
                var GroupModel = require('resources/group/model');

                return new GroupModel({
                    id: this.params.groupId
                });
            }
        },
        blocks: {
			breadcrumbs: require('./group__breadcrumbs'),
            table_productsProfit: require('blocks/table/productsProfit/productsProfit')
        }
    });
});