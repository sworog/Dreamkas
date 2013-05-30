define(
    [
        '/kit/editor/editor.js',
        '/kit/tooltip/tooltip.js',
        '/kit/form/form.js',
        '/collections/catalogClasses.js',
        '/models/catalogClass.js',
        '/routers/catalog.js',
        '/blocks/tooltip/tooltip_editClassMenu.js',
        '/blocks/tooltip/tooltip_editGroupMenu.js',
        './catalog.templates.js'
    ],
    function(Editor, Tooltip, Form, СatalogClassesCollection, CatalogClassModel, catalogRouter, Tooltip_editClassMenu, Tooltip_editGroupMenu, templates) {
        return Editor.extend({
            className: 'catalog',
            templates: templates,

            events: {
                'click .catalog__addClassLink': function(e) {
                    e.preventDefault();

                    var block = this,
                        $el = $(e.target);

                    block.addClassTooltip.show({
                        $trigger: $el
                    });

                    block.addClassForm.$el.find('[name="name"]').focus();
                },
                'click .catalog__editClassLink': function(e){
                    e.preventDefault();

                    var block = this,
                        $el = $(e.target);

                    block.tooltip_editClassMenu.show({
                        $trigger: $el
                    });

                    block.tooltip_editClassMenu.classModel = block.catalogClassesCollection.get($el.attr('classId'));
                },
                'click .catalog__editGroupLink': function(e){
                    e.preventDefault();

                    var block = this,
                        $el = $(e.target);

                    block.tooltip_editGroupMenu.show({
                        $trigger: $el
                    });

                    block.tooltip_editGroupMenu.classModel = block.catalogClassesCollection.get($el.closest('.catalog__classItem').attr('id'));
                    block.tooltip_editGroupMenu.groupId = $el.attr('groupId');
                }
            },

            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.catalogClassesCollection = new СatalogClassesCollection();

                block.tooltip_editClassMenu = new Tooltip_editClassMenu();
                block.tooltip_editGroupMenu = new Tooltip_editGroupMenu();

                block.addClassTooltip = new Tooltip({
                    addClass: 'catalog__addClassTooltip'
                });

                block.addClassForm = new Form({
                    model: new CatalogClassModel(),
                    templates: {
                        index: block.templates.addClassForm
                    },
                    addClass: 'catalog__addClassForm'
                });

                block.addClassTooltip.$content.html(block.addClassForm.$el);

                block.listenTo(block.catalogClassesCollection, {
                    reset: function() {
                        block.renderClassList();
                    },
                    add: function(model) {
                        block.$classList.prepend(block.templates.classItem({
                            block: block,
                            catalogClass: model.toJSON()
                        }));
                    },
                    remove: function(classModel){
                        block.$el
                            .find('#' + classModel.get('id'))
                            .remove();
                    },
                    change: function(classModel){
                        block.$el
                            .find('#' + classModel.get('id'))
                            .replaceWith(block.templates.classItem({
                                block: block,
                                catalogClass: classModel.toJSON()
                            }));
                    }
                });

                block.listenTo(block.addClassForm, {
                    successSubmit: function(model) {
                        block.catalogClassesCollection.push(model.toJSON());
                        block.addClassForm.clear();
                        block.addClassForm.$el.find('[name="name"]').focus();
                    }
                });

                block.catalogClassesCollection.fetch();
            },
            renderClassList: function() {
                var block = this;

                block.$classList
                    .html(block.templates.classList({
                        block: block,
                        catalogClasses: block.catalogClassesCollection.toJSON()
                    }));
            },
            'set:editMode': function(editMode){
                Editor.prototype['set:editMode'].apply(this, arguments);
                catalogRouter.params.editMode = editMode;
            }
        })
    }
);