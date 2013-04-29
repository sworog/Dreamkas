define(
    [
        'tpl!./productList.html',
        'tpl!./table.html',
        'tpl!./row.html'
    ],
    function(productList, table, row) {
        return {
            main: productList,
            table: table,
            row: row
        }
    }
);