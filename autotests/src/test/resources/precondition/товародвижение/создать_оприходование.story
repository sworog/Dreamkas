Meta:
@smoke
@precondition
@sprint_40
@us_103
@us_104
@us_104
@id_s40u103typeFilters1
@id_s40u103typeFilters2
@id_s40u103typeFilters3
@id_s40u103typeFilters4
@id_s40u103typeFilters5
@id_s40u103filterDates1
@id_s40u103filterDates2
@id_s40u103filterDates3

Scenario: Создание оприходования для списка товародвижения

Given пользователь создает апи объект оприходования с датой '26.08.2014', магазином с именем 'stockMovement-store2'
And пользователь добавляет к апи объекту оприходования продукт с именем 'stockMovement-product2', ценой '34.99', количеством '3'
And пользователь c электронным адресом 'stockMovement@lighthouse.pro' сохраняет апи объект оприходования