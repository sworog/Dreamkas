define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Catalog = require('blocks/catalog/catalog'),
        СatalogGroupsCollection = require('collections/catalogGroups');

    return Page.extend({
        pageName: 'page_catalog_catalog',
        templates: {
            '#content': require('tpl!./templates/catalog.html')
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

            page.catalogGroupsCollection = new СatalogGroupsCollection([], {
                storeId: page.storeId
            });

            $.when(page.catalogGroupsCollection.fetch()).then(function(){
                page.render();

                new Catalog({
                    editMode: page.editMode,
                    catalogGroupsCollection: page.catalogGroupsCollection,
                    el: document.getElementById('catalog')
                });
            });
        }
    });
});