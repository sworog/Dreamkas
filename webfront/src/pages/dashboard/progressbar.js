define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./progressbar.ejs'),
        width: function() {

            var width;

            switch (Number(this.activeStep)) {
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
                    break;
            }

            return width;
        }
    });
});