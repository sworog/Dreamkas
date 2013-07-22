define(function(require) {
    //requirements
    var Page = require('kit/page'),
        pageParams = require('pages/catalog/params'),
        СatalogGroupModel = require('models/catalogGroup'),
        CatalogGroup = require('blocks/catalogGroup/catalogGroup');

    return Page.extend({
        pageName: 'page_catalog_group',
        templates: {
            '#content': require('tpl!./templates/group.html')
        },
        permissions: {
            groups: 'GET'
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

            if (!LH.isAllow('groups', 'POST')) {
                pageParams.editMode = false;
            }

            page.catalogGroupModel = new СatalogGroupModel({
                id: catalogGroupId
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