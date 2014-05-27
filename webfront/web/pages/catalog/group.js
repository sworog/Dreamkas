define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        pageParams = require('pages/catalog/params'),
        СatalogGroupModel = require('models/catalogGroup'),
        CatalogGroup = require('blocks/catalogGroup/catalogGroup'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403'),
        router = require('router');

    return Page.extend({
        __name__: 'page_catalog_group',
        params: {
            catalogGroupId: null,
            editMode: null
        },
        partials: {
            '#content': require('tpl!./templates/group.html')
        },
        initialize: function(){
            var page = this;

            if (page.referrer.__name__ && page.referrer.__name__.indexOf('page_catalog') >= 0){
                _.extend(page.params, pageParams);
            } else {
                pageParams.editMode = page.params.editMode || pageParams.editMode || 'false'
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

            page.catalogGroupModel = new СatalogGroupModel({
                id: page.params.catalogGroupId,
                storeId: pageParams.storeId
            });

            $.when(page.catalogGroupModel.fetch()).then(function(){
                page.render();

                new CatalogGroup({
                    el: document.getElementById('catalogGroup'),
                    editMode: pageParams.editMode,
                    catalogGroupModel: page.catalogGroupModel
                });
            });
        }
    });
});