define(function(require) {
        //requirements
        var page = require('blocks/page/page'),
            BaseRouter = require('routers/baseRouter');

        var Router = BaseRouter.extend({
            routes: {
                'invoices': 'list',
                'invoice/list': 'list',
                'invoice/create': 'create',
                'invoice/:invoiceId': 'view'
            },
            view: function(invoiceId, params){
                page.open('/pages/invoice/view.html', {
                    invoiceId: invoiceId,
                    params: params || {}
                });
            },
            create: function(){
                page.open('/pages/invoice/create.html');
            },
            list: function(){
                page.open('/pages/invoice/list.html');
            }
        });

        return new Router();
    }
);