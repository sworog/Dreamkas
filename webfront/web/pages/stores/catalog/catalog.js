define(function(require) {
    //requirements
    var Page = require('pages/store'),
        exportCatalog = require('kit/exportCatalog/exportCatalog');

    return Page.extend({
        events: {
            'click .catalog__exportLink': function(e) {
                e.preventDefault();

                if (e.target.classList.contains('preloader_stripes')){
                    return;
                }

                e.target.classList.add('preloader_stripes');

                Promise.resolve(exportCatalog()).then(function(){
                    alert('Выгрузка началась');
                    e.target.classList.remove('preloader_stripes');
                }, function(){
                    alert('Выгрузка невозможна, обратитесь к администратору');
                    e.target.classList.remove('preloader_stripes');
                });
            }
        },
        partials: {
            content: require('ejs!./content.ejs')
        },
        collections: {
            groups: function(){
                var page = this,
                    GroupsCollections = require('collections/groups');

                return new GroupsCollections([], {
                    storeId: page.params.storeId
                });
            }
        }
    });
});