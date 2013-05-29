define(function(require) {
    return {
        index: require('tpl!./templates/catalogClass.html'),
        groupList: require('tpl!/blocks/catalog/templates/groupList.html'),
        groupItem: require('tpl!/blocks/catalog/templates/groupItem.html'),
        classList: require('tpl!./templates/classList.html'),
        addGroupForm: require('tpl!./templates/addGroupForm.html')
    }
});