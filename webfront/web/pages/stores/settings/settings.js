define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs')
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