define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_stores.ejs')
        },
        models: {
            store: require('models/store')
        },
        blocks: {
            form_store: function(){
                var page = this,
                    Form_store = require('blocks/form/form_store/form_store');

                return new Form_store({
                    model: page.models.store
                });
            }
        }
    });
});