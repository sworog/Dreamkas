define(function(require, exports, module) {
	//requirements
	var Modal_stockMovement = require('blocks/modal/stockMovement/stockMovement');

	return Modal_stockMovement.extend({
		id: 'modal_writeOff',
		warningForDeletedSupplier: 'Списание для удаленного поставщика.',
		warningForDeletedStore: 'Списание для удаленного магазина.',
		Model: require('resources/writeOff/model'),
		Form: require('blocks/form/stockMovement/writeOff/writeOff'),
		Form_products: require('blocks/form/stockMovementProducts/writeOff/writeOff'),
		addButtonCaption: 'Списать',
		addTitle: 'Списание товаров',
		editTitle: 'Редактирование списания товаров',
		removeCaption: 'Удалить списание',
		deletedTitle: 'Списание удалено',
		removeButtonText: 'Удалить списание'
	});
});