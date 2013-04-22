define(
    [
        '/views/kit/main.js',
        'tpl!./select_vat.html'
    ],
    function(Block, mainTpl) {
        return Block.extend({
            tpl: {
                main: mainTpl
            }
        });
    }
);