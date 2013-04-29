define(
    [
        'tpl!./balanceList.html',
        'tpl!./table.html',
        'tpl!./row.html'
    ],
    function(balanceList, table, row) {
        return {
            main: balanceList,
            table: table,
            row: row
        }
    }
);