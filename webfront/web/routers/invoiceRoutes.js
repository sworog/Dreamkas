define(
    [
        '/pages/page.js'
    ],
    function(page) {
        return {
            "invoice/view/:invoiceId": function(invoiceId) {
                page.open('/pages/invoice/view.html', {
                    invoiceId: invoiceId
                });
            }
        }
    }
);