define(function(require) {
    //requirements
    var Page = require('pages/page'),
        _ = require('underscore'),
        pageParams = require('pages/catalog/params'),
        Catalog = require('blocks/catalog/catalog'),
        СatalogClassesCollection = require('collections/catalogClasses');

    return Page.extend({
        pageName: 'page_catalog_catalog',
        templates: {
            '#content': require('tpl!./templates/catalog.html')
        },
        permissions: {
            klasses: 'GET'
        },
        initialize: function(params){
            var page = this;

            if (page.referer && page.referer.indexOf('page_catalog') >= 0){
                _.extend(pageParams, params);
            } else {
                _.extend(pageParams, {
                    editMode: false
                }, params)
            }

            page.catalogClassesCollection = new СatalogClassesCollection();

            $.when(page.catalogClassesCollection.fetch()).then(function(){
                page.render();

                new Catalog({
                    editMode: pageParams.editMode,
                    catalogClassesCollection: page.catalogClassesCollection,
                    el: document.getElementById('catalog')
                });
            });
        }
    });
});