define(
    [
        'tpl!./invoiceList.html',
        'tpl!./table.html',
        'tpl!./row.html'
    ],
    function(invoiceList, table, row) {
        return {
            main: invoiceList,
            table: table,
            row: row
        }
    }
);