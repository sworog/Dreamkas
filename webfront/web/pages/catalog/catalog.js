define(function(require) {
    //requirements
    var Page = require('pages/page'),
        _ = require('underscore'),
        pageParams = require('pages/catalog/params'),
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
        initialize: function(params){
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

            page.catalogGroupsCollection = new СatalogGroupsCollection();

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