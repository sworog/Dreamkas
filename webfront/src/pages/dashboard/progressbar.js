define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./progressbar.ejs'),
        setFillerWidth: function() {

            var block = this,
                $container = block.$('.page__dashboard__progressbarContainer'),
                $filler = block.$('.page__dashboard__progressbarFiller'),
                $completeIcon = block.$('.page__dashboard__progressbarCompleteIcon'),
                width;

            switch (Number(PAGE.activeStep)) {
                case 1:
                    width = 5;
                    break;
                case 2:
                    width = 51;
                    break;
                case 3:
                    width = 97;
                    break;
                case 4:
                    width = 100;
                    $container.css('overflow', 'hidden');
                    $completeIcon.show();
                    break;
            }

            $filler.width(width + '%');
        },
        render: function() {
            var block = this,
                render = Block.prototype.render.apply(block, arguments);

            block.setFillerWidth();

            return render;
        }
    });
});