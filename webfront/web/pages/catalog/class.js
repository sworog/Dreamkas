define(function(require) {
    //requirements
    var Page = require('pages/page'),
        CatalogClass = require('blocks/catalogClass/catalogClass');

    return Page.extend({
        pageName: 'page_catalogClass',
        templates: {
            '#content': require('tpl!./templates/class.html')
        },
        permissions: {
            klasses: 'get'
        },
        initialize: function(catalogClassId, params){
            var page = this;

            page.catalogClassId = catalogClassId;
            page.params = params || {};

            page.render();

            new CatalogClass({
                el: document.getElementById('catalogClass'),
                catalogClassId: page.catalogClassId,
                editMode: page.params.editMode
            })
        }
    });
});