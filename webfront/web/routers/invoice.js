define(
    [
        '/pages/page.js'
    ],
    function(page) {

        var Router = Backbone.Router.extend({
            routes: {
                'invoices': 'invoiceList',
                'invoice/list': 'invoiceList',
                'invoice/create': 'invoiceCreate',
                'invoice/view/:invoiceId': 'invoiceView',
                'invoice/:invoiceId': 'invoiceView'
            },
            invoiceView: function(invoiceId){
                page.open('/pages/invoice/view.html', {
                    invoiceId: invoiceId
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