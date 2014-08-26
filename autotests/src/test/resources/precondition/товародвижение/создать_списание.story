Meta:
@smoke
@precondition
@sprint_40
@us_103
@us_104
@id_s40u103typeFilters1
@id_s40u103typeFilters2
@id_s40u103typeFilters3
@id_s40u103filterDates1
@id_s40u103filterDates2
@id_s40u103filterDates3

Scenario: Cоздание списания для списка товародвижения

Given пользователь создает апи объект списания с датой '19.08.2014', магазином с именем 'stockMovement-store1'
And пользователь добавляет к апи объекту списания продукт с именем 'stockMovement-product1', ценой '11.99', количеством '2' и причиной 'Бой'
And пользователь c электронным адресом 'stockMovement@lighthouse.pro' сохраняет апи объект списания