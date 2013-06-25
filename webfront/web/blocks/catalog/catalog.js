define(function(require) {

        //requirements
        var Editor = require('kit/blocks/editor/editor'),
            Tooltip = require('kit/blocks/tooltip/tooltip'),
            Form = require('blocks/form/form'),
            Tooltip_editGroupMenu = require('blocks/tooltip/tooltip_editGroupMenu'),
            Tooltip_editClassMenu = require('blocks/tooltip/tooltip_editClassMenu'),
            catalogRouter = require('routers/catalog'),
            CatalogClassModel = require('models/catalogClass'),
            СatalogClassesCollection = require('collections/catalogClasses');

        return Editor.extend({
            className: 'catalog',
            blockName: 'catalog',
            templates: {
                index: require('tpl!./templates/catalog.html'),
                classList: require('tpl!./templates/classList.html'),
                classItem: require('tpl!./templates/classItem.html'),
                groupList: require('tpl!./templates/groupList.html'),
                groupItem: require('tpl!./templates/groupItem.html'),
                addClassForm: require('tpl!./templates/addClassForm.html')
            },

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

                    if (block.tooltip_editClassMenu){
                        block.tooltip_editClassMenu.tooltip_editClass.remove();
                        block.tooltip_editClassMenu.remove();
                    }

                    block.tooltip_editClassMenu = new Tooltip_editClassMenu({
                        $trigger: $el,
                        classModel: block.catalogClassesCollection.get($el.attr('classId'))
                    });

                    block.tooltip_editClassMenu.show();
                },
                'click .catalog__editGroupLink': function(e){
                    e.preventDefault();

                    var block = this,
                        $el = $(e.target);

                    //block.tooltip_editGroupMenu.tooltip_editGroup.remove();
                    if (block.tooltip_editGroupMenu){
                        block.tooltip_editGroupMenu.remove();
                    }

                    block.tooltip_editGroupMenu = new Tooltip_editGroupMenu({
                        $trigger: $el,
                        classModel: block.catalogClassesCollection.get($el.closest('.catalog__classItem').attr('id')),
                        groupId: $el.attr('groupId')
                    });

                    block.tooltip_editGroupMenu.show();
                }
            },

            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.catalogClassesCollection = new СatalogClassesCollection();

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