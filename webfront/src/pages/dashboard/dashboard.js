define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        Form_store = require('blocks/form/store/store'),
        Form_invoice = require('blocks/form/stockMovement/invoice/invoice'),
        Modal_store = require('blocks/modal/store/store'),
        Modal_invoice = require('blocks/modal/stockMovement/invoice/invoice'),
        firstStartResource = require('resources/firstStart/firstStart');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'dashboard',
        collections: {
            stores: require('resources/store/collection'),
            suppliers: require('resources/supplier/collection'),
            groups: require('resources/group/collection')
        },
        blocks: {
            steps: require('./steps'),
            info: require('./info'),

            modal_store: Modal_store.extend({
                blocks: {
                    form_store: Form_store.extend({
                        submit: function() {
                            return Form_store.prototype.submit.apply(this, arguments).then(function() {
                                return firstStartResource.fetch();
                            });
                        }
                    })
                }
            }),

            modal_invoice: Modal_invoice.extend({
                Form: Form_invoice.extend({
                    submit: function() {
                        return Form_invoice.prototype.submit.apply(this, arguments).then(function() {
                            return firstStartResource.fetch();
                        });
                    }
                })
            })

        }
    });
});
