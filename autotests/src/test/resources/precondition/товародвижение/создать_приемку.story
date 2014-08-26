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

Scenario: Создание накладной для списка товародвижения

Given пользователь создает апи объект накладной с датой '28.07.2014', статусом Оплачено 'false', магазином с именем 'stockMovement-store1', поставщиком с именем 'stockMovement-supplier1'
And пользователь добавляет продукт с именем 'stockMovement-product1', ценой '150' и количеством '5' к апи объекту накладной
And пользователь с адресом электронной почты 'stockMovement@lighthouse.pro' создает накладную через конструктор накладных