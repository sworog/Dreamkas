define(
    [
        '/kit/editor/editor.js',
        '/models/catalogClass.js',
        '/collections/catalogClasses.js',
        './catalogClass.templates.js'
    ],
    function(Editor, CatalogClassModel, СatalogClassesCollection, templates) {
        return Editor.extend({
            editMode: false,
            className: 'catalogClass',
            catalogClassId: null,
            templates: templates,

            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.catalogClassModel = new CatalogClassModel({
                    id: block.catalogClassId
                });

                block.catalogClassesCollection = new СatalogClassesCollection();

                block.listenTo(block.catalogClassModel, {
                    change: function(model) {
                        block.renderGroupList();
                        block.$className.html(model.get('name'));
                    }
                });

                block.listenTo(block.catalogClassesCollection, {
                    reset: function() {
                        block.renderClassList();
                    }
                });

                block.catalogClassModel.fetch();
                block.catalogClassesCollection.fetch();
            },
            renderGroupList: function() {
                var block = this;

                block.$groupList
                    .html(block.templates.groupList({
                        block: block
                    }));
            },
            renderClassList: function() {
                var block = this;

                block.$classList
                    .html(block.templates.classList({
                        block: block
                    }));
            }
        })
    }
);