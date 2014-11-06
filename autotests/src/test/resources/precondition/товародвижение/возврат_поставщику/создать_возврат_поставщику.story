Meta:
@smoke
@precondition
@sprint_40
@us_103
@us_104
@us_105
@id_s40u103typeFilters1
@id_s40u103typeFilters2
@id_s40u103typeFilters3
@id_s40u103typeFilters4
@id_s40u103typeFilters5
@id_s40u103filterDates1
@id_s40u103filterDates2
@id_s40u103filterDates3

Scenario: Cоздание возврата поставщику для списка товародвижения

Given пользователь создает апи объект возвратом поставщику с датой 'todayDate-3', статусом Оплачено 'true', магазином с именем 's40u105-store1', поставщиком с именем 's40u105-supplier1'
And пользователь добавляет продукт с именем 's40u105-product1', ценой '99.13' и количеством '10' к апи объекту возврата поставщику
And пользователь с адресом электронной почты 's40u105@lighthouse.pro' создает апи объект возврата поставщику