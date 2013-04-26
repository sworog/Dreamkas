define(
    [
        '/pages/page.js'
    ],
    function(page) {
        return {
            "product/edit/:productId": function(productId) {
                page.open('/pages/product/edit.html', {
                    productId: productId
                });
            },
            "product/create": function() {
                page.open('/pages/product/create.html');
            },
            "product/view/:productId": function(productId) {
                page.open('/pages/product/view.html', {
                    productId: productId
                });
            }
        }
    }
);