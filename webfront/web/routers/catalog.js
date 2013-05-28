define(
    [
        '/blocks/page/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {
        var Router = BaseRouter.extend({
            routes: {
                'catalog': 'catalog',
                'catalog/:classId': 'catalogClass',
                'catalog/:classId/:groupId': 'catalogGroup'
            },
            catalog: function(params) {
                page.open('/pages/catalog/catalog.html', {
                    params: params || {}
                });
            },
            catalogClass: function(classId, params){
                page.open('/pages/catalog/class.html', {
                    classId: classId,
                    params: params || {}
                });
            },
            catalogGroup: function(classId, groupId, params){
                page.open('/pages/catalog/group.html', {
                    classId: classId,
                    groupId: groupId,
                    params: params || {}
                });
            }
        });

        return new Router();
    }
);