define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        pageParams = require('pages/catalog/params'),
        СatalogGroupModel = require('models/catalogGroup'),
        CatalogGroup = require('blocks/catalogGroup/catalogGroup'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    var router = new Backbone.Router();

    return Page.extend({
        __name__: 'page_catalog_group',
        partials: {
            '#content': require('tpl!./templates/group.html')
        },
        initialize: function(params){
            var page = this;

            if (page.referrer.__name__ && page.referrer.__name__.indexOf('page_catalog') >= 0){
                _.extend(params, pageParams);
            } else {
                _.extend(pageParams, {
                    editMode: 'false'
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
                pageParams.editMode = 'false';
            }

            var route = router.toFragment(document.location.pathname, {
                editMode: pageParams.editMode,
                storeId: pageParams.storeId
            });

            router.navigate(route, {
                replace: true
            });

            page.catalogGroupModel = new СatalogGroupModel({
                id: params.catalogGroupId,
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