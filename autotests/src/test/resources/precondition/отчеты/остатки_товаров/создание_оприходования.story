Meta:
@smoke
@precondition
@sprint_43
@us_111.5

Scenario: Создание оприходования для списка отчета остатка товаров

Given пользователь создает апи объект оприходования с датой '26.08.2014', магазином с именем 'store-reports'
And пользователь добавляет к апи объекту оприходования продукт с именем 'reports-product1', ценой '34.99', количеством '3'
And пользователь c электронным адресом 'reports@lighthouse.pro' сохраняет апи объект оприходования