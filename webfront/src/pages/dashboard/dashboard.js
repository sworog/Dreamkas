define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'dashboard',
        collections: {
            stores: require('resources/store/collection'),
            suppliers: require('resources/supplier/collection'),
            groups: require('resources/group/collection')
        },
        resources: {
            firstStart: require('resources/firstStart/firstStart')
        },
        globalEvents: {
            'submit:success': function(data, block) {

                var page = this,
                    modal = block.$el.closest('.modal')[0];

                if (modal && !modal.referrer) {

                    page.resources.firstStart.fetch();
                }

            }
        },
        blocks: {
            progressbar: require('./progressbar'),
            steps: require('./steps'),
            info: require('./info'),
            modal_store: require('blocks/modal/store/store'),
            modal_invoice: require('blocks/modal/stockMovement/invoice/invoice')
        },
        initialize: function(){

            var page = this;

            page.listenTo(page.resources.firstStart, {
                fetched: function(){
                    page.render();
                }
            });

            return Page.prototype.initialize.apply(page, arguments);
        },
        render: function() {

            this.activeStep = this.getActiveStep();

            return Page.prototype.render.apply(this, arguments);
        },
        getActiveStep: function() {
            var page = this;

            var step1 = !page.resources.firstStart.data.length;

            var step2 = _.find(page.resources.firstStart.get('stores'), function(storeItem) {
                return storeItem.store;
            });

            var step3 = _.find(page.resources.firstStart.get('stores'), function(storeItem) {
                return storeItem.inventoryCostOfGoods;
            });

            var step4 = page.isUserReady();

            var activeStep;

            if (step1) {
                activeStep = 1;
            }

            if (step2) {
                activeStep = 2;
            }

            if (step3) {
                activeStep = 3;
            }

            if (step4) {
                activeStep = 4;
            }

            return activeStep;
        },
        isUserReady: function() {

            var page = this;

            return _.find(page.resources.firstStart.get('stores'), function(storeItem){
                return storeItem.sale;
            });
        }
    });
});
