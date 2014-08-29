Meta:
@smoke
@precondition
@sprint_40
@us_103
@id_s40u103typeFilters1
@id_s40u103typeFilters2
@id_s40u103typeFilters3
@id_s40u103filterDates1
@id_s40u103filterDates2
@id_s40u103filterDates3

Scenario: Cценарий для создания накладной для тестирования фильтрации дат

Given пользователь создает апи объект накладной с датой '28.07.2014', статусом Оплачено 'false', магазином с именем 's40u103-store', поставщиком с именем 's40u103-supplier'
And пользователь добавляет продукт с именем 's40u103-product1', ценой '150' и количеством '5' к апи объекту накладной
And пользователь с адресом электронной почты 's40u103@lighthouse.pro' создает накладную через конструктор накладных