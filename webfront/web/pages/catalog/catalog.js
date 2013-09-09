define(function(require) {
    //requirements
    var Page = require('kit/page'),
        pageParams = require('pages/catalog/params'),
        Catalog = require('blocks/catalog/catalog'),
        СatalogGroupsCollection = require('collections/catalogGroups'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/403/403');

    var router = new Backbone.Router();

    return Page.extend({
        pageName: 'page_catalog_catalog',
        templates: {
            '#content': require('tpl!./templates/catalog.html')
        },
        initialize: function(params){
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

            router.navigate(route, {
                replace: true
            });

            page.catalogGroupsCollection = new СatalogGroupsCollection([], {
                storeId: pageParams.storeId
            });

            $.when(page.catalogGroupsCollection.fetch()).then(function(){
                page.render();

                new Catalog({
                    editMode: pageParams.editMode,
                    catalogGroupsCollection: page.catalogGroupsCollection,
                    el: document.getElementById('catalog')
                });
            });
        }
    });
});