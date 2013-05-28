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
            catalogClassModel: new CatalogClassModel(),
            catalogClassesCollection: new СatalogClassesCollection(),
            templates: templates,

            initialize: function(){
                var block = this;

                Editor.prototype.initialize.call(block);

                block.catalogClassModel.id = block.catalogClassId;

                block.listenTo(block.catalogClassModel, {
                    reset: function() {
                        block.renderGroupList();
                    },
                    request: function() {
                        block.$groupList.addClass('preloader preloader_spinner');
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
            renderGroupList: function(){
                var block = this;

                block.$groupList
                    .html(block.templates.groupList({
                        block: block
                    }))
                    .removeClass('preloader preloader_spinner');
            },
            renderClassList: function(){
                var block = this;

                block.$classList
                    .html(block.templates.classList({
                        block: block
                    }));
            }
        })
    }
);