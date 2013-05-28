define(function(require) {
    return {
        index: require('tpl!./templates/catalogClass.html'),
        groupList: require('tpl!/blocks/catalog/templates/groupList.html'),
        classList: require('tpl!./templates/classList.html')
    }
});