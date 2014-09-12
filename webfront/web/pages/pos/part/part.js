define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
		events: {
			'click .page_pos__PartSidebarIcon': function(e) {
				var block = this;

				block.changeSidebarVisibility();
			}
		},
		models: {
			store: function(){
				var page = this,
					StoreModel = require('models/store/store');

				return new StoreModel({
					id: page.params.storeId
				});
			}
		},
		isSidebarVisible: true,
		changeSidebarVisibility: function() {
			var $sidebar = $('#sidebar');
			var $el = this.$el;

			if (this.isSidebarVisible)
			{
				$el.addClass('page_posPartHiddenSidebar');
				this.isSidebarVisible = false;
			}
			else
			{
				$el.removeClass('page_posPartHiddenSidebar');
				this.isSidebarVisible = true;
			}
		}
    });
});