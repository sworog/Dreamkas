define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        models: {
            grossSales: require('models/grossSales')
        },
        partials: {
            content: require('ejs!./content.ejs')
        },
        fetch: function(){
            return Promise.all([
                this.models.grossSales.fetch()
            ]);
        }
    });
});