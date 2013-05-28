define(
    [
        '/blocks/page/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {

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