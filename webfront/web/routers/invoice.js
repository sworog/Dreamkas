define(function(require) {
        //requirements
        var content_main = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        var Router = BaseRouter.extend({
            routes: {
                'invoices': 'list',
                'invoice/list': 'list',
                'invoice/create': 'create',
                'invoice/:invoiceId': 'view'
            },
            view: function(invoiceId, params){
                content_main.load('/pages/invoice/view.html', {
                    invoiceId: invoiceId,
                    params: params || {}
                });
            },
            create: function(){
                content_main.load('/pages/invoice/create.html');
            },
            list: function(){
                content_main.load('/pages/invoice/list.html');
            }
        });

        return new Router();
    }
);