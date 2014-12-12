define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        Form_store = require('blocks/form/store/store'),
        Form_invoice = require('blocks/form/stockMovement/invoice/invoice'),
        Modal_store = require('blocks/modal/store/store'),
        Modal_invoice = require('blocks/modal/stockMovement/invoice/invoice'),
        MainInfo = require('./mainInfo'),
        firstStartResource = require('resources/firstStart/firstStart');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'dashboard',

        params: {
            firstStart: 1
        },

        events: {
            'click .page_dashboard__finishButton': function(e){

                if (e.target.classList.contains('loading')){
                    return;
                }

                e.target.classList.add('loading');

                firstStartResource.put({
                    complete: true
                }).then(function(){
                    e.target.classList.remove('loading');
                });
            }
        },

        collections: {
            stores: require('resources/store/collection'),
            suppliers: require('resources/supplier/collection'),
            groups: require('resources/group/collection'),
            storesGrossSales: require('resources/storesGrossSales/collection')
        },

        resources: {
            firstStart: firstStartResource,
            productCount: require('resources/product/count'),
            grossSales: require('resources/grossSales/grossSales'),
            grossMargin: require('resources/grossMargin/grossMargin')
        },

        blocks: {
            progressbar: require('./progressbar'),
            steps: require('./steps'),
            info: require('./info'),
            grossSales: function(options) {

                options.title = 'Продажи по сети за сегодня на это время';
                options.resource = this.resources.grossSales;

                return new MainInfo(options);
            },
            grossMargin: function(options) {

                options.title = 'Прибыль по сети за сегодня на это время';
                options.resource = this.resources.grossMargin;

                return new MainInfo(options);
            },
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
            }),

            table_storesGrossSales: require('blocks/table/storesGrossSales/storesGrossSales'),
            table_topSales: require('blocks/table/topSales/topSales')
        },
        initialize: function() {

            var page = this;

            return Page.prototype.initialize.apply(page, arguments).then(function(){

                page.listenTo(page.resources.firstStart, {
                    reset: function() {
                        setTimeout(function(){
                            page.render();
                        }, 1);
                    }
                });
            });
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
                return storeItem.costOfInventory;
            });

            var step4 = _.find(page.resources.firstStart.get('stores'), function(storeItem) {
                return storeItem.sale;
            });

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
        }
    });
});
