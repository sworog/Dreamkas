define(function(require) {
    return {
        main: require('tpl!./writeOff.html'),
        table: require('tpl!./table.html'),
        row: require('tpl!./row.html'),
        head: require('tpl!./head.html'),
        footer: require('tpl!./footer.html'),
        removeConfirm: require('tpl!./removeConfirm.html'),
        dataInput: require('tpl!./dataInput.html'),
        dataInputControls: require('tpl!./dataInputControls.html'),
        dataInputAutocomplete: require('tpl!./dataInputAutocomplete.html')
    }
});