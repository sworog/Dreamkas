define(
    [
        'tpl!./main.html',
        'tpl!./head.html',
        'tpl!./table.html',
        'tpl!./row.html',
        'tpl!./footer.html'
    ],
    function(main, head, table, row, footer) {
        return {
            main: main,
            head: head,
            table: table,
            row: row,
            footer: footer
        }
    }
);