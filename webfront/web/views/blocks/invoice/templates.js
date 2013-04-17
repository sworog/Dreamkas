define(
    [
        'tpl!./main.html',
        'tpl!./head.html',
        'tpl!./table.html',
        'tpl!./row.html',
        'tpl!./footer.html',
        'tpl!./dataInput.html',
        'tpl!./dataInputControls.html'
    ],
    function(main, head, table, row, footer, dataInput, dataInputControls) {
        return {
            main: main,
            head: head,
            table: table,
            row: row,
            footer: footer,
            dataInput: dataInput,
            dataInputControls: dataInputControls
        }
    }
);