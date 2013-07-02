define(function(require) {
        //requirements
        var Tooltip_editGroupMenu = require('blocks/tooltip/tooltip_editGroupMenu'),
            Backbone = require('backbone'),
            Editor = require('kit/blocks/editor/editor'),
            Tooltip = require('kit/blocks/tooltip/tooltip'),
            CatalogClassModel = require('models/catalogClass'),
            CatalogGroupModel = require('models/catalogGroup'),
            СatalogClassesCollection = require('collections/catalogClasses'),
            ClassGroupsCollection = require('collections/classGroups'),
            params = require('pages/catalog/params'),
            Tooltip_editClassMenu = require('blocks/tooltip/tooltip_editClassMenu'),
            Form = require('blocks/form/form');

        var router = new Backbone.Router();

        return Editor.extend({
            editMode: false,
            className: 'catalogClass',
            addClass: 'catalog',
            catalogClassId: null,
            templates: {
                index: require('tpl!./templates/catalogClass.html'),
                groupList: require('tpl!blocks/catalog/templates/groupList.html'),
                groupItem: require('tpl!blocks/catalog/templates/groupItem.html'),
                classList: require('tpl!./templates/classList.html'),
                addGroupForm: require('tpl!./templates/addGroupForm.html')
            },

            events: {
                'click .catalog__addGroupLink': function(e) {
                    e.preventDefault();

                    var block = this,
                        $el = $(e.target);

                    block.addGroupTooltip.show({
                        $trigger: $el
                    });

                    block.addGroupForm.$el.find('[name="name"]').focus();
                },
                'click .catalog__editGroupLink': function(e){
                    e.preventDefault();

                    var block = this,
                        $el = $(e.target);

                    if (block.tooltip_editGroupMenu){
                        block.tooltip_editGroupMenu.remove();
                    }

                    block.tooltip_editGroupMenu = new Tooltip_editGroupMenu({
                        $trigger: $el,
                        classModel: block.catalogClassModel,
                        groupId: $el.attr('groupId')
                    });

                    block.tooltip_editGroupMenu.show();
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
                        classModel: block.catalogClassModel
                    });

                    block.tooltip_editClassMenu.show();
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
                    model: new CatalogGroupModel({
                        klass: block.catalogClassId
                    }),
                    templates: {
                        index: block.templates.addGroupForm
                    },
                    addClass: 'catalog__addGroupForm'
                });

                block.addGroupTooltip.$content.html(block.addGroupForm.$el);

                //listeners
                block.listenTo(block.catalogClassModel, {
                    change: function(model, opt) {
                        block.$className.html(model.get('name'));
                        block.classGroupsCollection.reset(block.catalogClassModel.get('groups'));
                        block.catalogClassesCollection.add(model.toJSON(), {
                            merge: true
                        });
                        block.renderClassList();
                    },
                    destroy: function(){
                        router.navigate('/catalog', {
                            trigger: true
                        })
                    }
                });

                block.listenTo(block.classGroupsCollection, {
                    reset: function() {
                        block.renderGroupList();
                    },
                    change: function() {
                        block.renderGroupList();
                    },
                    add: function(model, collection) {
                        block.catalogClassModel.set('groups', collection.toJSON())
                    }
                });

                block.listenTo(block.catalogClassesCollection, {
                    reset: function() {
                        block.renderClassList();
                    }
                });

                block.listenTo(block.addGroupForm, {
                    successSubmit: function(model) {
                        block.classGroupsCollection.push(model);
                        block.addGroupForm.clear();
                        block.addGroupForm.$el.find('[name="name"]').focus();
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
                        classGroups: block.classGroupsCollection.toJSON(),
                        catalogClass: block.catalogClassModel.toJSON()
                    }));
            },
            renderClassList: function() {
                var block = this;

                block.$classList
                    .html(block.templates.classList({
                        block: block
                    }));
            },
            'set:editMode': function(editMode){
                Editor.prototype['set:editMode'].apply(this, arguments);
                params.editMode = editMode;
            }
        })
    }
);