define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'dashboard',
        partials: {
            step: require('ejs!./step.ejs')
        },
        steps: [
            {
                title: 'Магазины',
                description: 'В этом разделе можно редактировать и создавать новые магазины',
                buttonCaption: 'ДОБАВИТЬ МАГАЗИН'
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
        ]
    });
});
