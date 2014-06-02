define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        SuppliersCollection = require('collections/suppliers'),
        currentUserModel = require('models/currentUser.inst');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function() {
            return !LH.isAllow('suppliers', 'GET');
        },
        initialize: function() {
            var page = this;

            page.collections = {
                suppliers: new SuppliersCollection()
            };

            $.when(page.collections.suppliers.fetch()).done(function() {
                page.render();
            });
        }
    });
});