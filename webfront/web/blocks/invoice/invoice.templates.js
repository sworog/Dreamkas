define(function(require) {
    return {
        index: require('tpl!./templates/invoice.html'),
        dataInput: require('tpl!./templates/dataInput.html'),
        dataInputAutocomplete: require('tpl!./templates/dataInputAutocomplete.html'),
        dataInputControls: require('tpl!./templates/dataInputControls.html'),
        footer: require('tpl!./templates/footer.html'),
        head: require('tpl!./templates/head.html'),
        removeConfirm: require('tpl!./templates/removeConfirm.html'),
        row: require('tpl!./templates/row.html'),
        table: require('tpl!./templates/table.html')
    }
});