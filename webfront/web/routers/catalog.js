define(
    [
        '/blocks/page/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {
        var Router = BaseRouter.extend({
            routes: {
                'catalog': 'catalog',
                'catalog/:catalogClassId': 'catalogClass',
                'catalog/:catalogClassId/:catalogGroupId': 'catalogGroup'
            },
            catalog: function(params) {
                page.open('/pages/catalog/catalog.html', {
                    params: params || {}
                });
            },
            catalogClass: function(catalogClassId, params){
                page.open('/pages/catalog/class.html', {
                    catalogClassId: catalogClassId,
                    params: params || {}
                });
            },
            catalogGroup: function(catalogClassId, catalogGroupId, params){
                page.open('/pages/catalog/group.html', {
                    catalogClassId: catalogClassId,
                    catalogGroupId: catalogGroupId,
                    params: params || {}
                });
            }
        });

        return new Router();
    }
);