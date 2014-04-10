define(function(require, exports, module) {
    //requirements
    var Page = require('page'),
        SuppliersCollection = require('collections/suppliers'),
        Form_invoice = require('blocks/form/form_invoice/form_invoice'),
        currentUserModel = require('models/currentUser');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!../localNavigation.html')
        },
        localNavigationActiveLink: 'create',
        params: {
            storeId: null
        },
        isAllow: function() {
            var page = this;

            return currentUserModel.stores.length && currentUserModel.stores.at(0).id === page.params.storeId;
        },
        collections: {
            suppliers: function(){
                return new SuppliersCollection();
            }
        },
        blocks: {
            form_invoice: function(){
                var page = this;

                return new Form_invoice({
                    collections: _.pick(page.collections, 'suppliers')
                });
            }
        }
    });
});