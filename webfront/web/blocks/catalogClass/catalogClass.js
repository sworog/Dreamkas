define(
    [
        '/kit/editor/editor.js',
        '/kit/form/form.js',
        '/kit/tooltip/tooltip.js',
        '/models/catalogClass.js',
        '/models/catalogGroup.js',
        '/collections/catalogClasses.js',
        '/collections/classGroups.js',
        './catalogClass.templates.js'
    ],
    function(Editor, Form, Tooltip, CatalogClassModel, CatalogGroupModel, СatalogClassesCollection, ClassGroupsCollection, templates) {

        return Editor.extend({
            editMode: false,
            className: 'catalogClass',
            addClass: 'catalog',
            catalogClassId: null,
            templates: templates,

            events: {
                'click .catalog__addGroupLink': function(e) {
                    e.preventDefault();

                    var block = this,
                        $el = $(e.target);

                    block.addGroupTooltip.show({
                        $trigger: $el
                    });

                    block.addGroupForm.$el.find('[name="name"]').focus();
                }
            },

            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.catalogClassModel = new CatalogClassModel({
                    id: block.catalogClassId
                });

                block.catalogClassesCollection = new СatalogClassesCollection();

                block.classGroupsCollection = new ClassGroupsCollection({
                    classId: block.catalogClassId
                });

                block.addGroupTooltip = new Tooltip({
                    addClass: 'catalog__addGroupTooltip'
                });

                block.addGroupForm = new Form({
                    model: new CatalogGroupModel(),
                    templates: {
                        index: block.templates.addGroupForm
                    },
                    addClass: 'catalog__addGroupForm'
                });

                block.addGroupTooltip.$content.html(block.addGroupForm.$el);

                //listeners
                block.listenTo(block.catalogClassModel, {
                    change: function(model) {
                        block.$className.html(model.get('name'));
                    }
                });

                block.listenTo(block.classGroupsCollection, {
                    reset: function() {
                        block.renderGroupList();
                    }
                });

                block.listenTo(block.catalogClassesCollection, {
                    reset: function() {
                        block.renderClassList();
                    }
                });

                block.listenTo(block.addGroupForm, {
                    successSubmit: function(model) {
                        block.catalogClassesCollection.push(model);
                        block.addClassForm.clear();
                        block.addClassForm.$el.find('[name="name"]').focus();
                    }
                });

                block.catalogClassModel.fetch();
                block.catalogClassesCollection.fetch();
                block.classGroupsCollection.fetch();
            },
            renderGroupList: function() {
                var block = this;

                block.$groupList
                    .html(block.templates.groupList({
                        block: block,
                        classGroups: block.classGroupsCollection.toJSON()
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