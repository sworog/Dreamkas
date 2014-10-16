define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        checkKey = require('kit/checkKey/checkKey');

    $(document)
        .on('click', function(e) {

            if (e.target.classList.contains('page__sideBarLink')){
                document.querySelector('.page').classList.toggle('page_sideBarVisible');
            } else if (!e.target.classList.contains('sideBar')) {
                document.querySelector('.page').classList.remove('page_sideBarVisible');
            }
        })
        .on('keyup', function(e) {

            if (checkKey(e.keyCode, ['ESC'])) {
                document.querySelector('.page').classList.remove('page_sideBarVisible');
            }
        });

    return Page.extend({
        template: require('ejs!./template.ejs'),
		models: {
			store: function(){
				var page = this,
					StoreModel = require('resources/store/model');

				return new StoreModel({
					id: page.params.storeId
				});
			}
		}
    });
});