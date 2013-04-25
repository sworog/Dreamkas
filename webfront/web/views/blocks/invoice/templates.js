define(
    [
        'tpl!./main.html',
        'tpl!./removeConfirm.html',
        'tpl!./head.html',
        'tpl!./table.html',
        'tpl!./row.html',
        'tpl!./footer.html',
        'tpl!./dataInput.html',
        'tpl!./dataInputControls.html',
        'tpl!./dataInputAutocomplete.html'
    ],
    function(main, removeConfirm, head, table, row, footer, dataInput, dataInputControls, dataInputAutocomplete) {
        return {
            main: main,
            removeConfirm: removeConfirm,
            head: head,
            table: table,
            row: row,
            footer: footer,
            dataInput: dataInput,
            dataInputControls: dataInputControls,
            dataInputAutocomplete: dataInputAutocomplete
        }
    }
);