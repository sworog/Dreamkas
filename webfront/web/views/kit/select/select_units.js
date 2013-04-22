define(
    [
        '/views/kit/main.js',
        'tpl!./select_units.html'
    ],
    function(Block, mainTpl) {
        return Block.extend({
            tpl: {
                main: mainTpl
            }
        });
    }
);