define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'dashboard',
        collections: {
            stores: require('resources/store/collection')
        },
        partials: {
            step: require('ejs!./step.ejs')
        },
        steps: [
            {
                title: 'Магазины',
                description: 'В этом разделе можно редактировать и создавать новые магазины',
                buttonCaption: 'ДОБАВИТЬ МАГАЗИН',
                buttonAttrs: {
                    'data-modal': 'modal_store',
                    'data-store-id': 0
                }
            },
            {
                title: 'Товары',
                description: 'В этом разделе можно оформить приёмку, оприходование, списание товара',
                buttonCaption: 'ПРИНЯТЬ ТОВАР'
            },
            {
                title: 'Продажа',
                description: 'В этом разделе можно оформить приёмку, оприходование, списание товара',
                buttonCaption: 'ЗАПУСТИТЬ КАССУ'
            }
        ],
        activeStep: function() {
            return 1;

            //this.collections.stores.length != 0 ? 2 : 1;
        },
        blocks: {
            modal_store: require('blocks/modal/store/store')
        }
    });
});
