define(function(require) {
    //requirements
    var Page = require('kit/page'),
        pageParams = require('pages/catalog/params'),
        СatalogGroupModel = require('models/catalogGroup'),
        CatalogGroup = require('blocks/catalogGroup/catalogGroup'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/403/403');

    var router = new Backbone.Router();

    return Page.extend({
        pageName: 'page_catalog_group',
        templates: {
            '#content': require('tpl!./templates/group.html')
        },
        initialize: function(catalogGroupId, params){
            var page = this;

            if (page.referer && page.referer.pageName.indexOf('page_catalog') >= 0){
                _.extend(pageParams, params);
            } else {
                _.extend(pageParams, {
                    editMode: false
                }, params)
            }

            if (currentUserModel.stores.length){
                pageParams.storeId = currentUserModel.stores.at(0).id;
            }

            if (!pageParams.storeId && !LH.isAllow('groups')){
                new Page403();
                return;
            }

            if (pageParams.storeId && !LH.isAllow('stores/{store}/groups')){
                new Page403();
                return;
            }

            if (!LH.isAllow('groups', 'POST')) {
                pageParams.editMode = false;
            }

            var route = router.toFragment(document.location.pathname, {
                editMode: pageParams.editMode,
                storeId: pageParams.storeId
            });

            router.navigate(route);

            page.catalogGroupModel = new СatalogGroupModel({
                id: catalogGroupId,
                storeId: pageParams.storeId
            });

            $.when(page.catalogGroupModel.fetch()).then(function(){
                page.render();

                console.log(page.catalogGroupModel);

                new CatalogGroup({
                    el: document.getElementById('catalogGroup'),
                    editMode: pageParams.editMode,
                    catalogGroupModel: page.catalogGroupModel
                });
            });
        }
    });
});