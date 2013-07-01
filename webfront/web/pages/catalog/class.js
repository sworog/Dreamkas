define(function(require) {
    //requirements
    var Page = require('pages/page'),
        _ = require('underscore'),
        pageParams = require('pages/catalog/params'),
        CatalogClass = require('blocks/catalogClass/catalogClass');

    return Page.extend({
        pageName: 'page_catalog_catalogClass',
        templates: {
            '#content': require('tpl!./templates/class.html')
        },
        permissions: {
            klasses: 'get'
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

            page.render();

            new CatalogClass({
                el: document.getElementById('catalogClass'),
                catalogClassId: page.catalogClassId,
                editMode: pageParams.editMode
            })
        }
    });
});