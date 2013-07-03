define(function(require) {
        //requirements
        var Tooltip_editGroupMenu = require('blocks/tooltip/tooltip_editGroupMenu'),
            Backbone = require('backbone'),
            Editor = require('kit/blocks/editor/editor'),
            Tooltip = require('kit/blocks/tooltip/tooltip'),
            CatalogGroupModel = require('models/catalogGroup'),
            params = require('pages/catalog/params'),
            Tooltip_editClassMenu = require('blocks/tooltip/tooltip_editClassMenu'),
            Form = require('blocks/form/form');

        var router = new Backbone.Router();

        return Editor.extend({
            editMode: false,
            className: 'catalogClass',
            addClass: 'catalog',
            templates: {
                index: require('tpl!./templates/catalogClass.html'),
                groupList: require('tpl!./templates/groupList.html'),
                groupItem: require('tpl!./templates/groupItem.html'),
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
            listeners: {
                catalogClassModel: {
                    change: function(model, opt) {
                        var block = this;

                        block.$className.html(model.get('name'));
                        block.renderClassList();
                    },
                    destroy: function(){
                        router.navigate('/catalog', {
                            trigger: true
                        })
                    }
                },
                classGroupsCollection: {
                    reset: function() {
                        var block = this;
                        block.renderGroupList();
                    },
                    change: function() {
                        var block = this;
                        block.renderGroupList();
                    },
                    add: function(){
                        var block = this;
                        block.renderGroupList();
                    }
                },
                catalogClassesCollection: {
                    reset: function() {
                        var block = this;
                        block.renderClassList();
                    }
                },
                addGroupForm: {
                    successSubmit: function(model) {
                        var block = this;
                        block.classGroupsCollection.push(model);
                        block.addGroupForm.clear();
                        block.addGroupForm.$el.find('[name="name"]').focus();
                    }
                }
            },
            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.addGroupTooltip = new Tooltip({
                    addClass: 'catalog__addGroupTooltip'
                });

                block.addGroupForm = new Form({
                    model: new CatalogGroupModel({
                        parentClassModel: block.catalogClassModel
                    }),
                    templates: {
                        index: block.templates.addGroupForm
                    },
                    addClass: 'catalog__addGroupForm'
                });

                block.addGroupTooltip.$content.html(block.addGroupForm.$el);
            },
            renderGroupList: function() {
                var block = this;

                block.$groupList
                    .html(block.templates.groupList({
                        block: block,
                        groupsCollection: block.classGroupsCollection
                    }));
            },
            renderClassList: function() {
                var block = this;

                block.$classList
                    .html(block.templates.classList({
                        block: block,
                        currentClassModel: block.catalogClassModel
                    }));
            },
            'set:editMode': function(editMode){
                Editor.prototype['set:editMode'].apply(this, arguments);
                params.editMode = editMode;
            }
        })
    }
);