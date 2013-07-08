define(function(require) {
    //requirements
    var Page = require('pages/page'),
        _ = require('underscore'),
        pageParams = require('pages/catalog/params'),
        СatalogGroupsCollection = require('collections/catalogGroups'),
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

            if (page.referer && page.referer.indexOf('page_catalog') >= 0){
                _.extend(pageParams, params);
            } else {
                _.extend(pageParams, {
                    editMode: false
                }, params)
            }

            page.catalogGroupId = catalogGroupId;
            page.catalogGroupsCollection = new СatalogGroupsCollection();

            $.when(page.catalogGroupsCollection.fetch()).then(function(){
                page.render();

                page.catalogGroupModel = page.catalogGroupsCollection.get(page.catalogGroupId);
                page.classGroupsCollection = page.catalogGroupModel.groups;

                new CatalogGroup({
                    el: document.getElementById('catalogGroup'),
                    editMode: pageParams.editMode,
                    catalogGroupsCollection: page.catalogGroupsCollection,
                    catalogGroupModel: page.catalogGroupModel,
                    classGroupsCollection: page.classGroupsCollection
                });
            });
        }
    });
});