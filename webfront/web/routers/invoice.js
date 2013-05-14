define(
    [
        '/pages/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {

        var Router = BaseRouter.extend({
            routes: {
                'invoices': 'invoiceList',
                'invoice/list': 'invoiceList',
                'invoice/create': 'invoiceCreate',
                'invoice/:invoiceId': 'invoiceView'
            },
            invoiceView: function(invoiceId, params){
                page.open('/pages/invoice/view.html', {
                    invoiceId: invoiceId,
                    params: params || {}
                });
            },
            invoiceCreate: function(){
                page.open('/pages/invoice/create.html');
            },
            invoiceList: function(){
                page.open('/pages/invoice/list.html');
            }
        });

        return new Router();
    }
);