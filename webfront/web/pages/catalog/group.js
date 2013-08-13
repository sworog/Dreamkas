define(function(require) {
    //requirements
    var Page = require('kit/page'),
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
        initialize: function(){
            var page = this;

            if (page.referer && page.referer.pageName.indexOf('page_catalog') >= 0){
                page.editMode = page.referer.editMode;
            }

            if (!LH.isAllow('groups', 'POST')) {
                page.editMode = false;
            }

            page.catalogGroupModel = new СatalogGroupModel({
                id: page.catalogGroupId,
                storeId: page.storeId
            });

            $.when(page.catalogGroupModel.fetch()).then(function(){
                page.render();

                new CatalogGroup({
                    el: document.getElementById('catalogGroup'),
                    editMode: page.editMode,
                    catalogGroupModel: page.catalogGroupModel
                });
            });
        }
    });
});