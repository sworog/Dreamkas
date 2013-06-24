define(function(require) {
        //requirements
        var content_main = require('blocks/content/content_main'),
            deepExtend = require('kit/utils/deepExtend'),
            BaseRouter = require('routers/baseRouter');

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
                content_main.load(pageUrl, deepExtend({} , opt, {
                    params: this.params
                }));
            },
            catalog: function(params) {
                this.open('/pages/catalog/templates/catalog.html', {
                    params: params
                });
            },
            catalogClass: function(catalogClassId, params){
                this.open('/pages/catalog/templates/class.html', {
                    catalogClassId: catalogClassId,
                    params: params
                });
            },
            catalogGroup: function(catalogClassId, catalogGroupId, params){
                this.open('/pages/catalog/templates/group.html', {
                    catalogClassId: catalogClassId,
                    catalogGroupId: catalogGroupId,
                    params: params
                });
            }
        });

        return new Router();
    }
);