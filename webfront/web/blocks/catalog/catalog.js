define(
    [
        '/kit/editor/editor.js',
        '/collections/catalogClasses.js',
        './catalog.templates.js'
    ],
    function(Editor, СatalogClassesCollection, templates) {
        return Editor.extend({
            className: 'catalog',
            catalogClassesCollection: new СatalogClassesCollection(),
            templates: templates,

            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.listenTo(block.catalogClassesCollection, {
                    reset: function() {
                        block.renderClassList();
                    },
                    request: function() {
                        block.$classList.addClass('preloader preloader_spinner');
                    }
                });

                block.catalogClassesCollection.fetch();
            },
            renderClassList: function() {
                var block = this;

                block.$classList
                    .html(block.templates.classList({
                        block: block
                    }))
                    .removeClass('preloader preloader_spinner');
            }
        })
    }
);