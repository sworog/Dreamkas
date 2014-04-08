define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        pageParams = require('pages/catalog/params'),
        Catalog = require('blocks/catalog/catalog'),
        СatalogGroupsCollection = require('collections/catalogGroups'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403'),
        router = require('router');

    return Page.extend({
        __name__: 'page_catalog_catalog',
        partials: {
            '#content': require('tpl!./templates/catalog.html')
        },
        initialize: function(params){
            var page = this;

            if (page.referrer.__name__ && page.referrer.__name__.indexOf('page_catalog') >= 0){
                _.extend(params, pageParams);
            } else {
                pageParams.editMode = params.editMode || pageParams.editMode || 'false'
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
                pageParams.editMode = 'false';
            }

            router.navigate(document.location.pathname + '?editMode=' + pageParams.editMode + '&storeId=' + pageParams.storeId, {
                replace: true,
                trigger: false
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