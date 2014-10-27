define(function(require, exports, module) {
	//requirements
	var Modal_stockMovement = require('blocks/modal/stockMovement/stockMovement');

	return Modal_stockMovement.extend({
		id: 'modal_writeOff',
		formId: 'form_writeOff',
		Model: require('resources/writeOff/model'),
		Form: require('blocks/form/writeOff/writeOff'),
		Form_products: require('blocks/form/stockMovementProducts/writeOff/writeOff'),
		addButtonCaption: 'Списать',
		addTitle: 'Списание товаров',
		editTitle: 'Редактирование списания товаров',
		removeCaption: 'Удалить списание',
		deletedTitle: 'Списание успешно удалено.'
	});
});