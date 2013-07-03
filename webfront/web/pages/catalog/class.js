define(function(require) {
    //requirements
    var Page = require('pages/page'),
        _ = require('underscore'),
        pageParams = require('pages/catalog/params'),
        СatalogClassesCollection = require('collections/catalogClasses'),
        CatalogClass = require('blocks/catalogClass/catalogClass');

    return Page.extend({
        pageName: 'page_catalog_catalogClass',
        templates: {
            '#content': require('tpl!./templates/class.html')
        },
        permissions: {
            klasses: 'GET'
        },
        initialize: function(catalogClassId, params){
            var page = this;

            if (page.referer && page.referer.indexOf('page_catalog') >= 0){
                _.extend(pageParams, params);
            } else {
                _.extend(pageParams, {
                    editMode: false
                }, params)
            }

            page.catalogClassId = catalogClassId;
            page.catalogClassesCollection = new СatalogClassesCollection();

            $.when(page.catalogClassesCollection.fetch()).then(function(){
                page.render();

                page.catalogClassModel = page.catalogClassesCollection.get(page.catalogClassId);
                page.classGroupsCollection = page.catalogClassModel.groups;

                new CatalogClass({
                    el: document.getElementById('catalogClass'),
                    editMode: pageParams.editMode,
                    catalogClassesCollection: page.catalogClassesCollection,
                    catalogClassModel: page.catalogClassModel,
                    classGroupsCollection: page.classGroupsCollection
                });
            });
        }
    });
});