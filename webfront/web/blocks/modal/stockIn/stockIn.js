define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            stockIn: require('models/stockIn/stockIn')
        },
        events: {
            'click .stockIn__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.models.stockIn.destroy().then(function() {
                    e.target.classList.remove('loading');
                });

            }
        },
        blocks: {
            form_stockIn: require('blocks/form/stockIn/stockIn'),
            form_stockInProducts: require('blocks/form/stockInProducts/stockInProducts')
        }
    });
});