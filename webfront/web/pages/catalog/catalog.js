define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Catalog = require('blocks/catalog/catalog');

    return Page.extend({
        pageName: 'page_catalog',
        templates: {
            '#content': require('tpl!./templates/catalog.html')
        },
        permissions: {
            klasses: 'get'
        },
        initialize: function(params){
            var page = this;

            page.params = params || {};

            page.render();

            new Catalog({
                editMode: page.params.editMode,
                el: document.getElementById('catalog')
            });
        }
    });
});