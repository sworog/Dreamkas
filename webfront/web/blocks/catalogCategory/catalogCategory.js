define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        Editor = require('kit/blocks/editor/editor'),
        Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu'),
        params = require('pages/catalog/params');

    var router = new Backbone.Router();

    return Editor.extend({
        blockName: 'catalogCategory',
        catalogCategoryModel: null,
        catalogSubcategoryId: null,
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/index.html')
        },
        events: {
        },
        listeners: {
        },
        initialize: function() {
            var block = this;

            Editor.prototype.initialize.call(block);

            if (block.catalogSubcategoryId){
                block.set('catalogSubcategoryId', block.catalogSubcategoryId);
            }

            block.tooltip_catalogCategoryMenu = new Tooltip_catalogCategoryMenu();
        },
        'set:editMode': function(editMode) {
            Editor.prototype['set:editMode'].apply(this, arguments);
            params.editMode = editMode;
        },
        remove: function(){
            var block = this;

            block.tooltip_catalogCategoryMenu.remove();

            Editor.prototype.remove.call(block);
        },
        'set:catalogSubcategoryId': function(catalogSubcategoryId){
            var block = this;
        }
    });
});