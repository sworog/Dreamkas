define(function(require) {
    return {
        index: require('tpl!./templates/catalog.html'),
        classList: require('tpl!./templates/classList.html'),
        classItem: require('tpl!./templates/classItem.html'),
        groupList: require('tpl!./templates/groupList.html'),
        groupItem: require('tpl!./templates/groupItem.html'),
        addClassForm: require('tpl!./templates/addClassForm.html')
    }
});