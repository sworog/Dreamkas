define(function(require, exports, module) {
    //requirements
    var Modal_stockMovement = require('blocks/modal/stockMovement/stockMovement');

    return Modal_stockMovement.extend({
		id: 'modal_invoice',
		Model: require('resources/invoice/model'),
		Form: require('blocks/form/stockMovement/invoice/invoice'),
		Form_products: require('blocks/form/stockMovementProducts/invoice/invoice'),
		addButtonCaption: 'Принять',
		addTitle: 'Приёмка товаров от поставщика',
		editTitle: 'Редактирование приёмки товаров от поставщика',
		removeCaption: 'Удалить приемку',
		deletedTitle: 'Приемка удалена'
    });
});