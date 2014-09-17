define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        checkKey = require('kit/checkKey/checkKey');

    $(document)
        .on('click', '.page__sideBarLink', function(e) {
            e.preventDefault();

            document.querySelector('.page').classList.toggle('page_sideBarVisible');
        })
        .on('click', function(e) {

            if (!e.target.classList.contains('page__sideBarLink')){
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
					StoreModel = require('models/store/store');

				return new StoreModel({
					id: page.params.storeId
				});
			}
		}
    });
});