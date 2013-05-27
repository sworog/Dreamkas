define(
    [
        '/kit/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {
        var Router = BaseRouter.extend({
            routes: {
                'catalog': 'catalog'
            },
            catalog: function(classId, groupId, params) {
                page.open('/pages/catalog/catalog.html', {
                    params: params || {}
                });
            }
        });

        return new Router();
    }
);