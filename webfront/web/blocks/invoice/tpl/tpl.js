define(function(require) {
    return {
        main: require('tpl!./invoice.html'),
        removeConfirm: require('tpl!./removeConfirm.html'),
        head: require('tpl!./head.html'),
        table: require('tpl!./table.html'),
        row: require('tpl!./row.html'),
        footer: require('tpl!./footer.html'),
        dataInput: require('tpl!./dataInput.html'),
        dataInputControls: require('tpl!./dataInputControls.html'),
        dataInputAutocomplete: require('tpl!./dataInputAutocomplete.html')
    }
});