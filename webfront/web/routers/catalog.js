define(
    [
        '/blocks/page/page.js',
        '/kit/_utils/deepExtend.js',
        './baseRouter.js'
    ],
    function(page, deepExtend, BaseRouter) {

        var Router = BaseRouter.extend({
            params: {
                editMode: false
            },
            routes: {
                'catalog': 'catalog',
                'catalog/:catalogClassId': 'catalogClass',
                'catalog/:catalogClassId/:catalogGroupId': 'catalogGroup'
            },
            open: function(pageUrl, opt){
                page.open(pageUrl, deepExtend({} , opt, {
                    params: this.params
                }));
            },
            catalog: function(params) {
                this.open('/pages/catalog/catalog.html', {
                    params: params
                });
            },
            catalogClass: function(catalogClassId, params){
                this.open('/pages/catalog/class.html', {
                    catalogClassId: catalogClassId,
                    params: params
                });
            },
            catalogGroup: function(catalogClassId, catalogGroupId, params){
                this.open('/pages/catalog/group.html', {
                    catalogClassId: catalogClassId,
                    catalogGroupId: catalogGroupId,
                    params: params
                });
            }
        });

        return new Router();
    }
);